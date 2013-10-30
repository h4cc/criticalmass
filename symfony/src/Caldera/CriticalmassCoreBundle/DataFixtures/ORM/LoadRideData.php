<?php

namespace Caldera\CriticalmassCoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Caldera\CriticalmassCoreBundle\Entity\Ride;

class LoadRideData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
        $ride = new Ride();
        $ride->setCity($this->getReference("city-wedel"));
        $ride->setDate(new \DateTime("2013-05-31"));
        $ride->setTime(new \DateTime("19:00:00"));
        $ride->setLocation("Rathaus");
        $ride->setHasLocation(true);
        $ride->setHasTime(true);
        $ride->setLatitude(53.5810);
        $ride->setLongitude(9.7037);

        $manager->persist($ride);
        $manager->flush();

        $this->addReference("city-wedel-ride-2013-05-31", $ride);

		// Beispiel-Touren der Critical Mass Hamburg
		$ride = new Ride();
		$ride->setCity($this->getReference("city-hamburg"));
		$ride->setDate(new \DateTime("2013-05-31"));
		$ride->setTime(new \DateTime("19:00:00"));
		$ride->setLocation("tba");
        $ride->setHasLocation(true);
        $ride->setHasTime(true);
        $ride->setLatitude(53.54561);
        $ride->setLongitude(9.95427);

		$manager->persist($ride);
		$manager->flush();

		$this->addReference("city-hamburg-ride-2013-05-31", $ride);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 2;
	}
}
