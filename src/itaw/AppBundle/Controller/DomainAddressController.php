<?php

namespace itaw\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DomainAddressController extends Controller
{
    public function latestAction($limit)
    {
        $addresses = $this->getDoctrine()->getRepository('itawDataBundle:DomainAddress')->findBy(
            array(),
            array(
                'openDate' => 'desc'
            ),
            $limit
        );

        return $this->render('itawAppBundle:DomainAddress:latest.html.twig', array(
                'addresses' => $addresses
            ));
    }
}
