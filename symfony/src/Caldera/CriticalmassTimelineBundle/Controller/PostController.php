<?php

namespace Caldera\CriticalmassTimelineBundle\Controller;

use Caldera\CriticalmassTimelineBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function writeAction(Request $request, $cityId = null, $rideId = null)
    {
        $post = new Post();
        $formBuilder = $this->createFormBuilder($post)
            ->add('message', 'textarea')
            ->add('latitude', 'hidden')
            ->add('longitude', 'hidden');

        $ride = null;
        $city = null;

        if ($cityId)
        {
            $formBuilder->setAction($this->generateUrl('caldera_criticalmass_timeline_post_write_city', array('cityId' => $cityId)));
            $city = $this->getDoctrine()->getRepository('CalderaCriticalmassCoreBundle:City')->find($cityId);
            $post->setCity($city);

            $redirectUrl = $this->generateUrl('caldera_criticalmass_desktop_city_show', array('citySlug' => $city->getMainSlugString()));
        }
        elseif ($rideId)
        {
            $formBuilder->setAction($this->generateUrl('caldera_criticalmass_timeline_post_write_ride', array('rideId' => $rideId)));
            $ride = $this->getDoctrine()->getRepository('CalderaCriticalmassCoreBundle:Ride')->find($rideId);
            $post->setRide($ride);

            $redirectUrl = $this->generateUrl('caldera_criticalmass_desktop_ride_show', array('citySlug' => $ride->getCity()->getMainSlugString(), 'rideDate' => $ride->getFormattedDate()));
        }
        else
        {
            $formBuilder->setAction($this->generateUrl('caldera_criticalmass_timeline_post_write'));
            $redirectUrl = $this->generateUrl('caldera_criticalmass_timeline_list');
        }

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush();

            /* Using the user’s referer will not work as the user might come from the writefailed page and would be
               redirected there again. */
            return new RedirectResponse($redirectUrl);
        }
        elseif ($form->isSubmitted())
        {
            return $this->render('CalderaCriticalmassTimelineBundle:Post:writefailed.html.twig', array('form' => $form->createView(), 'ride' => $ride, 'city' => $city));
        }

        return $this->render('CalderaCriticalmassTimelineBundle:Post:write.html.twig', array('form' => $form->createView()));
    }


    public function listAction(Request $request, $cityId = null, $rideId = null)
    {
        $criteria = array('enabled' => true);

        if ($cityId)
        {
            $criteria['city'] = $cityId;
        }

        if ($rideId)
        {
            $criteria['ride'] = $rideId;
        }

        $posts = $this->getDoctrine()->getRepository('CalderaCriticalmassTimelineBundle:Post')->findBy($criteria, array('dateTime' => 'DESC'));

        return $this->render('CalderaCriticalmassTimelineBundle:Post:list.html.twig', array('posts' => $posts));
    }
}
