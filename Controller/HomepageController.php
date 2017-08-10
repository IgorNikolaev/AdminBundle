<?php

namespace Igor\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    public function indexAction()
    {
        $sections = [];

        foreach ($this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata() as $meta) {
            $parts = explode('\\', strtolower($meta->getName()));
            $offset = array_search('entity', $parts);

            if (false !== $offset) {
                $parts = array_slice($parts, $offset + 1);
            }

            $prev = null;

            foreach ($parts as $key => $part) {
                if ($part === $prev) {
                    unset($parts[$key]);
                }

                $prev = $part;
            }

            $name = implode(' ', $parts);

            $sections[] = [
                'name' => $name,
                'slug' => strtolower(str_replace('\\', '/', $meta->getName())),
            ];
        }

        return $this->render('IgorAdminBundle:Homepage:index.html.twig', [
            'sections' => $sections,
        ]);
    }
}
