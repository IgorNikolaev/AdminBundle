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
use Symfony\Component\Form\FormError;
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

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($form->getData());
                $manager->flush();

                $this->addFlash('success', sprintf('%s created.', $section->getName()));

                return $this->redirectToRoute('igor_admin_crud_index', [
                    'alias' => $alias,
                ]);
            }

            $this->addFlash('error', 'Form error occurred.');
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
        $entity = $this->getEntity($section, $id);

        $response = $this->redirectToRoute('igor_admin_crud_index', [
            'alias' => $alias,
        ]);

        $form = $this->getAdminFormFactory()->createDeleteForm($section, $entity)->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($entity);
            $manager->flush();

            $this->addFlash('success', sprintf('%s deleted.', $section->getName()));

            return $response;
        }

        $this->addFlash('error', implode(PHP_EOL, array_map(function (FormError $error) {
            return $error->getMessage();
        }, iterator_to_array($form->getErrors(true)))));

        return $response;
    }

    /**
     * @param \Igor\AdminBundle\Section\Section $section Section
     * @param string                            $id      Entity ID
     *
     * @return object
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getEntity(Section $section, string $id)
    {
        $entity = $this->getDoctrine()->getManager()->find($section->getMetadata()->getName(), $id);

        if (empty($entity)) {
            throw $this->createNotFoundException(sprintf('Unable to find %s by ID "%s".', $section->getName(), $id));
        }

        return $entity;
    }

    /**
     * @param string $alias Section alias
     *
     * @return \Igor\AdminBundle\Section\Section
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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
