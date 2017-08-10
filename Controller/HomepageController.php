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
use Symfony\Component\HttpFoundation\Response;

/**
 * Homepage controller
 */
class HomepageController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(): Response
    {
        $sections = $this->getSectionPool()->getSections();

        return $this->render('IgorAdminBundle:Homepage:index.html.twig', [
            'sections' => $sections,
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
