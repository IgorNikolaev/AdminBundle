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
 * Section factory
 */
class SectionFactory
{
    /**
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata Metadata
     *
     * @return \Igor\AdminBundle\Section\Section
     */
    public function createSection(ClassMetadata $metadata): Section
    {
        return new Section($this->generateAlias($metadata->getName()), $this->generateName($metadata->getName()));
    }

    /**
     * @param string $class Entity class
     *
     * @return string
     */
    private function generateAlias($class): string
    {
        return strtolower(str_replace('\\', '/', $class));
    }

    /**
     * @param string $class Entity class
     *
     * @return string
     */
    private function generateName($class): string
    {
        $parts = explode('\\', strtolower($class));
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

        return implode(' ', $parts);
    }
}
