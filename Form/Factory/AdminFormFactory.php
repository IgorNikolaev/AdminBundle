<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Igor Nikolaev
 * @link      http://www.penguin33.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Igor\AdminBundle\Form\Factory;

use Igor\AdminBundle\Form\Type\AdminType;
use Igor\AdminBundle\Section\Section;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\RouterInterface;

/**
 * Admin form factory
 */
class AdminFormFactory
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $genericFormFactory;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $genericFormFactory Generic form factory
     * @param \Symfony\Component\Routing\RouterInterface   $router             Router
     */
    public function __construct(FormFactoryInterface $genericFormFactory, RouterInterface $router)
    {
        $this->genericFormFactory = $genericFormFactory;
        $this->router = $router;
    }

    /**
     * @param \Igor\AdminBundle\Section\Section $section Section
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNewForm(Section $section): FormInterface
    {
        return $this->genericFormFactory->create(AdminType::class, $section->getMetadata()->getReflectionClass()->newInstance(), [
            'section' => $section,
            'action'  => $this->router->generate('igor_admin_crud_new', [
                'alias' => $section->getAlias(),
            ]),
        ]);
    }

    /**
     * @param \Igor\AdminBundle\Section\Section $section  Section
     * @param object[]                          $entities Entities
     *
     * @return \Symfony\Component\Form\FormView[]
     */
    public function createDeleteFormViews(Section $section, array $entities): array
    {
        $views = [];

        foreach ($entities as $entity) {
            $views[] = $this->createDeleteFormView($section, $entity);
        }

        return $views;
    }

    /**
     * @param \Igor\AdminBundle\Section\Section $section Section
     * @param object                            $entity  Entity
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function createDeleteFormView(Section $section, $entity): FormView
    {
        return $this->createDeleteForm($section, $entity)->createView();
    }

    /**
     * @param \Igor\AdminBundle\Section\Section $section Section
     * @param object                            $entity  Entity
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteForm(Section $section, $entity): FormInterface
    {
        $id = implode('', $section->getMetadata()->getIdentifierValues($entity));

        $builder = $this->genericFormFactory->createNamedBuilder(
            sprintf('delete_%s', $id),
            FormType::class,
            [
                'id' => $id,
            ],
            [
                'action' => $this->router->generate('igor_admin_crud_delete', [
                    'alias' => $section->getAlias(),
                    'id'    => $id,
                ]),
            ]
        );

        return $builder
            ->add('id', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Delete',
            ])
            ->getForm();
    }
}
