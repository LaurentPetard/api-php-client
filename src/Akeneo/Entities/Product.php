<?php

namespace Akeneo\Entities;

/**
 * Class Product
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Product
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
    public function getIdentifier()
    {
        return $this->getProperty('identifier');
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return $this->getProperty('family');
    }

    /**
     * @return string[]
     */
    public function getGroups()
    {
        return $this->getProperty('groups', []);
    }

    /**
     * @return string
     */
    public function getVariantGroup()
    {
        return $this->getProperty('variantGroup');
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        return $this->getProperty('categories', []);
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getProperty('enabled', false);
    }

    /**
     * @param string|null $attributeCode
     *
     * @return mixed
     */
    public function getProductValues($attributeCode = null)
    {
        $productValues = $this->getProperty('values', []);

        if (null === $attributeCode) {
            return $productValues;
        }

        if (!isset($productValues[$attributeCode])) {
            throw new \InvalidArgumentException(sprintf('There is not any product value for the attribute code "%s".', $attributeCode));
        }

        return $productValues[$attributeCode];
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
            throw new \InvalidArgumentException(
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
        return isset($this->properties['values'][$attributeCode]) && count($this->properties['values'][$attributeCode]) > 0;
    }

    /**
     * @return boolean
     */
    public function hasProductValue($attributeCode, $locale = null, $channel = null)
    {
        if (!isset($this->properties['values'][$attributeCode])) {
            return false;
        }

        $productValues = $this->properties['values'][$attributeCode];

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
        $this->properties['identifier'] = $identifier;

        return $this;
    }

    /**
     * @param string $family
     */
    public function setFamily($family)
    {
        $this->properties['family'] = $family;

        return $this;
    }

    /**
     * @param string $group
     */
    public function addGroup($group)
    {
        if (!isset($this->properties['groups'])) {
            $this->properties['groups'] = [];
        }

        if (!in_array($group, $this->properties['groups'])) {
            $this->properties['groups'][] = $group;
        }

        return $this;
    }

    /**
     * @param string $group
     */
    public function deleteGroup($group)
    {
        if (!isset($this->properties['groups'])) {
            return $this;
        }

        $index = array_search($group, $this->properties['groups']);
        if (false !== $index) {
            unset($this->properties['groups'][$index]);
        }

        return $this;
    }

    /**
     * @param string $variantGroup
     */
    public function setVariantGroup($variantGroup)
    {
        $this->properties['variant_group'] = $variantGroup;

        return $this;
    }

    /**
     * @param string[] $category
     */
    public function addCategory($category)
    {
        if(!isset($this->properties['categories'])) {
            $this->properties['categories'] = [];
        }

        if (!in_array($category, $this->properties['categories'])) {
            $this->properties['categories'][] = $category;
        }

        return $this;
    }

    /**
     * @param string $category
     */
    public function deleteCategory($category)
    {
        if(!isset($this->properties['categories'])) {
            return $this;
        }

        $index = array_search($category, $this->properties['categories']);
        if (false !== $index) {
            unset($this->properties['categories'][$index]);
        }

        return $this;
    }

    public function enable()
    {
        $this->properties['enabled'] = true;

        return $this;
    }

    public function disable()
    {
        $this->properties['enabled'] = false;

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

        if (!isset($this->properties['values'][$attributeCode])) {
            $this->properties['values'][$attributeCode] = [];
        }

        $this->properties['values'][$attributeCode][] = new ProductValue($attributeCode, $data, $locale, $channel);

        return $this;
    }

    public function deleteProductValue($attributeCode, $locale = null, $channel = null)
    {
        if (!isset($this->properties['values'][$attributeCode])) {
            return $this;
        }

        $productValues = $this->properties['values'][$attributeCode];

        foreach ($productValues as $key => $productValue) {
            if ($locale === $productValue->getLocale() && $channel === $productValue->getChannel()) {
                unset($this->properties['values'][$attributeCode][$key]);

                return $this;
            }
        }

        return $this;
    }

    public function toArray()
    {
        $arrayProduct = $this->properties;

        if(isset($arrayProduct['values'])) {
            $arrayProduct['values'] = array_map(function($productValues) {
                return array_map(function($productValue) {
                    return $productValue->toArray();
                }, $productValues);
            }, $arrayProduct['values']);
        }

        return $arrayProduct;
    }

    /**
     * @param string $property
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    protected function getProperty($property, $defaultValue = null)
    {
        if(array_key_exists($property, $this->properties)) {
            return $this->properties[$property];
        }

        return $defaultValue;
    }
}
