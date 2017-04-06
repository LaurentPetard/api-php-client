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
    /** @var string */
    protected $code;

    /** @var string */
    protected $parent;

    /** @var array */
    protected $labels;

    /** @var array */
    protected $links;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function getLabel($locale)
    {
        return isset($this->labels[$locale]) ? $this->labels[$locale] : null;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param string $linkName
     *
     * @return array|null
     */
    public function getLink($linkName)
    {
        return isset($this->links[$linkName]) ? $this->links[$linkName] : null;
    }

    /**
     * @param string $code
     *
     * @return Category
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string $parent
     *
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

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
        $this->labels[$locale] = $label;

        return $this;
    }

    /**
     * @param $rel
     * @param $url
     *
     * @return Category
     */
    public function addLink($rel, $url)
    {
        $this->links[$rel] = $url;

        return $this;
    }
}
