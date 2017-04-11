<?php

namespace Akeneo\Entities;

/**
 * Class Category
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Category
{
    /** @var array */
    protected $properties;

    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->getProperty('code');
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->getProperty('parent');
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getLabel($locale)
    {
        return isset($this->properties['labels'][$locale]) ? $this->properties['labels'][$locale] : null;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->getProperty('labels');
    }

    /**
     * @param string $linkName
     *
     * @return array|null
     */
    public function getLink($linkName)
    {
        return isset($this->properties['_links'][$linkName]) ? $this->properties['_links'][$linkName] : null;
    }

    /**
     * @param string $code
     *
     * @return Category
     */
    public function setCode($code)
    {
        $this->properties['code'] = $code;

        return $this;
    }

    /**
     * @param string $parent
     *
     * @return Category
     */
    public function setParent($parent)
    {
        $this->properties['parent'] = $parent;

        return $this;
    }

    /**
     * @param string $locale
     * @param string $label
     *
     * @return Category
     */
    public function addLabel($locale, $label)
    {
        if (!isset($this->properties['labels'])) {
            $this->properties['labels'] = [];
        }

        $this->properties['labels'][$locale] = $label;

        return $this;
    }

    public function toArray()
    {
        $properties = $this->properties;

        if (isset($properties['_links'])) {
            unset($properties['_links']);
        }

        return $properties;
    }

    /**
     * @param string $property
     *
     * @return mixed|null
     */
    protected function getProperty($property)
    {
        if(array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }

        return null;
    }
}
