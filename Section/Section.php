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

use Doctrine\Common\Persistence\Mapping\ClassMetadata;

/**
 * Section
 */
class Section
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @var \Doctrine\Common\Persistence\Mapping\ClassMetadata
     */
    private $metadata;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $properties;

    /**
     * @param string                                             $alias    Alias
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata Metadata
     * @param string                                             $name     Name
     */
    public function __construct(string $alias, ClassMetadata $metadata, string $name)
    {
        $this->metadata = $metadata;
        $this->alias = $alias;
        $this->name = $name;

        $this->properties = array_unique(
            array_merge($metadata->getIdentifier(), $metadata->getAssociationNames(), $metadata->getFieldNames())
        );
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return \Doctrine\Common\Persistence\Mapping\ClassMetadata
     */
    public function getMetadata(): ClassMetadata
    {
        return $this->metadata;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
