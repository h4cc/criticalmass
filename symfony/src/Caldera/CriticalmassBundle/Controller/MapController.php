<?php

namespace Caldera\CriticalmassBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Caldera\CriticalmassBundle\Utility as Utility;
use Caldera\CriticalmassBundle\Entity as Entity;

class MapController extends Controller
{
	public function mapdataAction($citySlug)
	{
		$city = $city = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:CitySlug')->findOneBySlug($citySlug)->getCity();
//		$city = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:CitySlug')->findOneBySlug($this->getRequest()->getSession()->get('currentCitySlug'))->getCity();
		$ride = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:Ride')->findOneBy(array('city' => $city->getId()), array('date' => 'DESC'));
		/*
		if ($ride->getGodMode() == 1)
		{
		$totalPositions = $this->getDoctrine()->getRepository("CalderaCriticalmassBundle:Position")->createQueryBuilder('p')
		->where("p.ride = :rideId")
		->andWhere("p.user = :userId")
   ->setParameter('rideId', $ride->getId())
   ->setParameter('userId', 7)
   ->add('orderBy', 'p.creationDateTime DESC')
   ->setMaxResults(10)
   ->getQuery()
   ->getResult();
		}
		else
		{
                $totalPositions = $this->getDoctrine()->getRepository("CalderaCriticalmassBundle:Position")->createQueryBuilder('p')
                ->where("p.ride = :rideId")
   ->setParameter('rideId', $ride->getId())
   ->add('orderBy', 'p.creationDateTime DESC')
   ->setMaxResults(10)
   ->getQuery()
   ->getResult();
		}*/

		$lmp = new Utility\MapBuilder\LiveMapBuilder(
			$ride,
			$this->getDoctrine()
		);

		$lmp->calculateMainPositions();
		$lmp->calculateAdditionalPositions();

		$response = new Response();
		$response->setContent(json_encode($lmp->draw()));

		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	public function ridelocationAction($citySlug)
	{
		$city = $city = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:CitySlug')->findOneBySlug($citySlug)->getCity();
		$ride = $this->get('caldera_criticalmass_ride_repository')->findOneBy(array('city' => $city->getId()), array('date' => 'DESC'));

		$response = new Response();

		if ($ride->getHasLocation())
		{
			$response->setContent(json_encode(array(
				'latitude' => $ride->getLatitude(),
				'longitude' => $ride->getLongitude()
			)));
		}

		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	public function citylocationAction($citySlug)
	{
		$city = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:CitySlug')->findOneBySlug($citySlug)->getCity();

		$response = new Response();

		$response->setContent(json_encode(array(
			'latitude' => $city->getLatitude(),
			'longitude' => $city->getLongitude()
		)));

		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	public function citylocationbyidAction($cityId)
	{
		$city = $this->getDoctrine()->getRepository('CalderaCriticalmassBundle:City')->findOneById($cityId);

		$response = new Response();

		$response->setContent(json_encode(array(
			'latitude' => $city->getLatitude(),
			'longitude' => $city->getLongitude()
		)));

		return $response;
	}
}
