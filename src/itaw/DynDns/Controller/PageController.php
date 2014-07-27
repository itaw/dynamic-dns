<?php

namespace itaw\DynDns\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController
{

    public function indexAction()
    {
        return new Response('lol');
    }

}
