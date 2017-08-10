<?php

namespace Igor\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    public function indexAction()
    {
        $sections = $this->getSectionPool()->getSections();

        return $this->render('IgorAdminBundle:Homepage:index.html.twig', [
            'sections' => $sections,
        ]);
    }

    /**
     * @return \Igor\AdminBundle\Section\SectionPool
     */
    private function getSectionPool()
    {
        return $this->get('igor_admin.section.pool');
    }
}
