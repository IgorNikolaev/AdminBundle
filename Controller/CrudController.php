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

use Igor\AdminBundle\Section\SectionPool;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CRUD controller
 */
class CrudController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request Request
     * @param string                                    $alias   Section alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, string $alias): Response
    {
        $section = $this->getSectionPool()->findSection($alias);

        if (empty($section)) {
            throw $this->createNotFoundException(sprintf('Unable to find admin section by alias "%s".', $alias));
        }

        return $this->render('IgorAdminBundle:Crud:create.html.twig', [
            'section' => $section,
        ]);
    }

    /**
     * @return \Igor\AdminBundle\Section\SectionPool
     */
    private function getSectionPool(): SectionPool
    {
        return $this->get('igor_admin.section.pool');
    }
}
