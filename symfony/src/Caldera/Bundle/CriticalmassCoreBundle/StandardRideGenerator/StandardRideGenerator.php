<?php

namespace Caldera\Bundle\CriticalmassCoreBundle\StandardRideGenerator;

use Caldera\Bundle\CalderaBundle\Entity\Ride;

class StandardRideGenerator {
    protected $year;
    protected $month;
    protected $city;
    protected $ride;

    public function __construct($city, $year, $month)
    {
        $this->year = $year;
        $this->month = $month;
        $this->city = $city;

        $this->ride = new Ride();
        $this->ride->setCity($city);
    }

    public function execute()
    {
        if (!$this->city->getIsStandardable())
        {
            return null;
        }
        
        $this->ride->setDateTime(new \DateTime($this->year.'-'.$this->month.'-01 00:00:00'));

        $this->calculateDate();
        $this->calculateTime();
        $this->calculateLocation();

        return $this->ride;
    }

    protected function calculateDate()
    {
        $dateTime = $this->ride->getDateTime();

        $dayInterval = new \DateInterval('P1D');

        while ($dateTime->format('w') != $this->city->getStandardDayOfWeek())
        {
            $dateTime->add($dayInterval);
        }

        if ($this->city->getStandardWeekOfMonth() > 0)
        {
            $weekInterval = new \DateInterval('P7D');

            for ($weekOfMonth = 1; $weekOfMonth < $this->city->getStandardWeekOfMonth(); ++$weekOfMonth)
            {
                $dateTime->add($weekInterval);
            }
        }
        else
        {
            $weekInterval = new \DateInterval('P7D');

            while ($dateTime->format('m') == $this->month)
            {
                $dateTime->add($weekInterval);
            }

            $dateTime->sub($weekInterval);
        }

        $this->ride->setDateTime($dateTime);
    }

    protected function calculateTime()
    {
        $this->ride->setHasTime($this->city->getStandardTime() != null);

        if ($this->city->getStandardTime())
        {
            $timeInterval = new \DateInterval('PT'.$this->city->getStandardTime()->format('H').'H'.$this->city->getStandardTime()->format('i').'M');
            $this->ride->setDateTime($this->ride->getDateTime()->add($timeInterval));
        }
    }

    protected function calculateLocation()
    {
        if ($this->city->getStandardLocation() && $this->city->getStandardLatitude() && $this->city->getStandardLongitude())
        {
            $this->ride->setLocation($this->city->getStandardLocation());
            $this->ride->setLatitude($this->city->getStandardLatitude());
            $this->ride->setLongitude($this->city->getStandardLongitude());
            $this->ride->setHasLocation(true);
        }
        else
        {
            $this->ride->setHasLocation(false);
        }
    }

    public function isRideDuplicate()
    {
        foreach ($this->city->getRides() as $ride)
        {
            if ($ride->isSameRide($this->ride))
            {
                return true;
            }
        }

        return false;
    }
} 