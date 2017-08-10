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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
        return $this->genericFormFactory->create(AdminType::class, null, [
            'section' => $section,
            'action'  => $this->router->generate('igor_admin_crud_new', [
                'alias' => $section->getAlias(),
            ]),
        ]);
    }
}
