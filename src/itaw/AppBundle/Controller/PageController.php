<?php

namespace itaw\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PageController
 * @package itaw\AppBundle\Controller
 */
class PageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        return $this->redirectToRoute('dashboard');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction()
    {
        $users = $this->getDoctrine()->getRepository('itawUserBundle:User')->findAll();
        $domains = $this->getDoctrine()->getRepository('itawDataBundle:Domain')->findAll();

        return $this->render(
            'itawAppBundle:Page:dashboard.html.twig',
            array(
                'users' => $users,
                'domains' => $domains
            )
        );
    }
}