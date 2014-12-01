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
        return $this->render('itawAppBundle:Page:dashboard.html.twig');
    }
}