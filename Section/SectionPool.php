<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Igor Nikolaev
 * @link      http://www.penguin33.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Igor\AdminBundle\Section;

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;

/**
 * Section pool
 */
class SectionPool
{
    /**
     * @var \Doctrine\Common\Persistence\Mapping\ClassMetadataFactory
     */
    private $metadataFactory;

    /**
     * @var \Igor\AdminBundle\Section\SectionFactory
     */
    private $sectionFactory;

    /**
     * @var \Igor\AdminBundle\Section\Section[]|null
     */
    private $sections;

    /**
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadataFactory $metadataFactory Metadata factory
     * @param \Igor\AdminBundle\Section\SectionFactory                  $sectionFactory  Section factory
     */
    public function __construct(ClassMetadataFactory $metadataFactory, SectionFactory $sectionFactory)
    {
        $this->metadataFactory = $metadataFactory;
        $this->sectionFactory = $sectionFactory;

        $this->sections = null;
    }

    /**
     * @param string $alias Alias
     *
     * @return \Igor\AdminBundle\Section\Section|null
     */
    public function findSection(string $alias): ?Section
    {
        $sections = $this->getSections();

        return isset($sections[$alias]) ? $sections[$alias] : null;
    }

    /**
     * @return \Igor\AdminBundle\Section\Section[]
     */
    public function getSections(): array
    {
        if (null === $this->sections) {
            $sections = [];

            foreach ($this->metadataFactory->getAllMetadata() as $metadata) {
                $section = $this->sectionFactory->createSection($metadata);
                $sections[$section->getAlias()] = $section;
            }

            $this->sections = $sections;
        }

        return $this->sections;
    }
}
