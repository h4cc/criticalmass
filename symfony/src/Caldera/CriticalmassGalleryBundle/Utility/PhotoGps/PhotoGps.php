<?php

namespace Caldera\CriticalmassGalleryBundle\Utility\PhotoGps;

use Caldera\CriticalmassCoreBundle\Entity\Track;
use Caldera\CriticalmassCoreBundle\Utility\GpxReader\GpxReader;
use Caldera\CriticalmassGalleryBundle\Entity\Photo;
use Caldera\CriticalmassGalleryBundle\Utility\Gps\GpsConverter;

class PhotoGps {
    protected $track;
    protected $photo;
    protected $exifData;
    
    public function __construct()
    {

    }

    public function setPhoto(Photo $photo)
    {
        $this->photo = $photo;
    }
    
    public function setTrack(Track $track)
    {
        $this->track = $track;
    }
    
    public function execute()
    {
        $this->readExifData();
        
        if ($this->exifData)
        {
            $this->readFromExifData();
        }
        elseif ($this->track)
        {
            $this->approximateCoordinates();
        }
    }
    
    public function readExifData()
    {
        $this->exifData = exif_read_data($this->photo->getFilePath(), 0, true);
    }
    
    public function readFromExifData()
    {
        $gc = new GpsConverter();

        if (isset($this->exifData['GPS']['GPSLatitude']) && isset($this->exifData['GPS']['GPSLongitude']))
        {
            $this->photo->setLatitude($gc->convert($this->exifData['GPS']['GPSLatitude']));
            $this->photo->setLongitude($gc->convert($this->exifData['GPS']['GPSLongitude']));
        }

        /*if (isset($this->exifData['GPS']['GPSTimeStamp']) && isset($this->exifData['GPS']['GPSDateStamp'])) {
            $this->photo->setDateTime(new \DateTime(str_replace(":", "-", $this->exifData['GPS']['GPSDateStamp'])
                . ' ' . preg_replace("#[/].*#", "", $this->exifData['GPS']['GPSTimeStamp'][0]) . ":" .
                preg_replace("#[/].*#", "", $this->exifData['GPS']['GPSTimeStamp'][1]) . ":" .
                preg_replace("#[/].*#", "", $this->exifData['GPS']['GPSTimeStamp'][2])));
        }*/
    }

    protected function xmlToDateTime($xml)
    {
        return new \DateTime(str_replace("T", " ", str_replace("Z", "", $xml)));
    }

    function timeDiffinSec($difference) {
        return $difference->format('%s') + 60 * $difference->format("%i") + 3600 * $difference->format("%H");
    }

    function interpolate($firstPoint, $secondPoint, $i, $j) {
        $n = $i + $j;
        return floatval($firstPoint * (($n - $i) / floatval($n))) +
        floatval($secondPoint * (($n - $j) / floatval($n)));
    }

    public function approximateCoordinates()
    {
        $gpxReader = new GpxReader();
        
        $this->track->loadGpx();
        $gpxReader->loadString($this->track->getGpx());
        
        $finished = 0;
        
        for ($i = 0; $i < ($gpxReader->countPoints() - 1) && !($finished); $i++)
        {
            $tmpDatetimePrev = $this->xmlToDateTime($gpxReader->getTimestampOfPoint($i));
            $tmpDatetimeSucc = $this->xmlToDateTime($gpxReader->getTimestampOfPoint($i + 1));
            
            if (($tmpDatetimePrev <= $this->photo->getDateTime()) &&
                ($tmpDatetimeSucc >= $this->photo->getDateTime()))
            {
                $timeDiffPrev = $this->timeDiffInSec($tmpDatetimePrev->diff($this->photo->getDateTime()));
                $timeDiffSucc = $this->timeDiffinSec($tmpDatetimeSucc->diff($this->photo->getDateTime()));

                $this->photo->setLatitude($this->interpolate($gpxReader->getLatitudeOfPoint($i),
                    $gpxReader->getLatitudeOfPoint($i+1),
                    $timeDiffPrev,
                    $timeDiffSucc));
                $this->photo->setLongitude($this->interpolate($gpxReader->getLongitudeOfPoint($i),
                    $gpxReader->getLongitudeOfPoint($i+1),
                    $timeDiffPrev,
                    $timeDiffSucc));
                $finished = 1;
            }
        }
    }
}