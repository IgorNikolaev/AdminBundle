<?php

namespace Igor\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    public function indexAction()
    {
        return $this->render('IgorAdminBundle:Homepage:index.html.twig');
    }
}
