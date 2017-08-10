<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Igor Nikolaev
 * @link      http://www.penguin33.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Igor\AdminBundle\Controller;

use Igor\AdminBundle\Form\Factory\AdminFormFactory;
use Igor\AdminBundle\Section\Section;
use Igor\AdminBundle\Section\SectionPool;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CRUD controller
 */
class CrudController extends Controller
{
    /**
     * @param string $alias Section alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(string $alias): Response
    {
        $section = $this->getSection($alias);
        $entities = $this->getDoctrine()->getManager()->getRepository($section->getMetadata()->getName())->findAll();
        $deleteForms = $this->getAdminFormFactory()->createDeleteFormViews($section, $entities);

        return $this->render('IgorAdminBundle:Crud:index.html.twig', [
            'delete_forms' => $deleteForms,
            'entities'     => $entities,
            'section'      => $section,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     * @param string                                    $alias   Section alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, string $alias): Response
    {
        $section = $this->getSection($alias);

        $form = $this->getAdminFormFactory()->createNewForm($section)->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($form->getData());
            $manager->flush();

            $this->addFlash('success', sprintf('%s created.', $section->getName()));

            return $this->redirectToRoute('igor_admin_crud_index', [
                'alias' => $alias,
            ]);
        }

        return $this->render('IgorAdminBundle:Crud:new.html.twig', [
            'form'    => $form->createView(),
            'section' => $section,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     * @param string                                    $alias   Section alias
     * @param string                                    $id      Entity ID
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, string $alias, string $id): RedirectResponse
    {
        $section = $this->getSection($alias);

        $this->addFlash('success', sprintf('%s deleted.', $section->getName()));

        return $this->redirectToRoute('igor_admin_crud_index', [
            'alias' => $alias,
        ]);
    }

    /**
     * @param string $alias Section alias
     *
     * @return \Igor\AdminBundle\Section\Section
     */
    private function getSection(string $alias): Section
    {
        $section = $this->getSectionPool()->findSection($alias);

        if (empty($section)) {
            throw $this->createNotFoundException(sprintf('Unable to find admin section by alias "%s".', $alias));
        }

        return $section;
    }

    /**
     * @return \Igor\AdminBundle\Form\Factory\AdminFormFactory
     */
    private function getAdminFormFactory(): AdminFormFactory
    {
        return $this->get('igor_admin.form.factory');
    }

    /**
     * @return \Igor\AdminBundle\Section\SectionPool
     */
    private function getSectionPool(): SectionPool
    {
        return $this->get('igor_admin.section.pool');
    }
}
