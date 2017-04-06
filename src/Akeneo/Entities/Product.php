<?php

namespace Akeneo\Entities;

use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class Product
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Product
{
    /** @var string */
    protected $identifier;

    /** @var string */
    protected $family;

    /** @var string[] */
    protected $groups = [];

    /** @var string */
    protected $variantGroup;

    /** @var string[] */
    protected $categories = [];

    /** @var boolean */
    protected $enabled = true;

    /** @var array */
    protected $productValues = [];


    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @return string[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function getVariantGroup()
    {
        return $this->variantGroup;
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return array
     */
    public function getProductValues($attributeCode = null)
    {
        if (null === $attributeCode) {
            return $this->productValues;
        }

        if (!isset($this->productValues[$attributeCode])) {
            throw new InvalidArgumentException(sprintf('There is not any product value for the attribute code "%s".', $attributeCode));
        }

        return $this->productValues[$attributeCode];
    }

    /**
     * @return array
     */
    public function getProductValue($attributeCode, $locale = null, $channel = null)
    {
        $productValues = $this->getProductValues($attributeCode);

        $productValue = array_filter($productValues, function($productValue) use ($locale, $channel) {
            return $locale === $productValue['locale'] && $channel === $productValue['scope'];
        });

        if (1 !== count($productValue)) {
            throw new InvalidArgumentException(
                sprintf(
                    'There is 0 or more than one product value for the attribute "%s", locale "%s" and channel "%s".',
                    $attributeCode,
                    $locale,
                    $channel
                )
            );
        }

        return $productValue;
    }

    /**
     * @return boolean
     */
    public function hasProductValues($attributeCode)
    {
        return isset($this->productValues[$attributeCode]) && count($this->productValues[$attributeCode]) > 0;
    }

    /**
     * @return boolean
     */
    public function hasProductValue($attributeCode, $locale = null, $channel = null)
    {
        if (!isset($this->productValues[$attributeCode])) {
            return false;
        }

        $productValues = $this->productValues[$attributeCode];

        foreach ($productValues as $productValue) {
            if ($locale === $productValue->getLocale() && $channel === $productValue->getChannel()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @param string $family
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * @param string $group
     */
    public function addGroup($group)
    {
        if (!in_array($group, $this->groups)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * @param string $group
     */
    public function deleteGroup($group)
    {
        $index = array_search($group, $this->groups);
        if (false !== $index) {
            unset($this->groups[$index]);
        }

        return $this;
    }

    /**
     * @param string $variantGroup
     */
    public function setVariantGroup($variantGroup)
    {
        $this->variantGroup = $variantGroup;

        return $this;
    }

    /**
     * @param string[] $category
     */
    public function addCategory($category)
    {
        if (!in_array($category, $this->categories)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    /**
     * @param string $category
     */
    public function deleteCategory($category)
    {
        $index = array_search($category, $this->categories);
        if (false !== $index) {
            unset($this->categories[$index]);
        }

        return $this;
    }

    public function enable()
    {
        $this->enabled = true;

        return $this;
    }

    public function disable()
    {
        $this->enabled = false;

        return $this;
    }


    /**
     * Set a product value for given attribute, locale and channel.
     * If the product value already exists, it overwrites the value.
     *
     * @param string $attributeCode
     * @param mixed  $data
     * @param string $locale
     * @param string $channel
     */
    public function setProductValue($attributeCode, $data, $locale = null, $channel = null)
    {
        if ($this->hasProductValue($attributeCode, $locale, $channel)) {
            $this->deleteProductValue($attributeCode, $locale, $channel);
        }

        $this->productValues[$attributeCode][] = new ProductValue($data, $locale, $channel);

        return $this;
    }

    public function deleteProductValue($attributeCode, $locale = null, $channel = null)
    {
        if (isset($this->productValues[$attributeCode])) {
            return $this;
        }

        $productValues = $this->productValues[$attributeCode];

        foreach ($productValues as $key => $productValue) {
            if ($locale === $productValue->getLocale() && $channel === $productValue->getChannel()) {
                unset($this->productValues[$attributeCode][$key]);

                return $this;
            }
        }

        return $this;
    }
}
