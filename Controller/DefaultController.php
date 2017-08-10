<?php

namespace Igor\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IgorAdminBundle:Default:index.html.twig');
    }
}
