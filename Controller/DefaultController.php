<?php

namespace FOQ\TyperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FOQTyperBundle:Default:index.html.twig');
    }
}
