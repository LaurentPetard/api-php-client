<?php

namespace Akeneo\Entities;

/**
 * Class ProductValue
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductValue
{
    /** @var string */
    protected $attributeCode;

    /** @var mixed */
    protected $data;

    /** @var string */
    protected $locale;

    /** @var string */
    protected $channel;

    /**
     * @param string $attributeCode
     * @param string $locale
     * @param string $channel
     * @param string $data
     */
    public function __construct($attributeCode, $data, $locale = null, $channel = null)
    {
        $this->attributeCode = $attributeCode;
        $this->data = $data;
        $this->locale = $locale;
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getAttributeCode()
    {
        return $this->attributeCode;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return mixed
     */
    public function setData()
    {
        return $this->data;
    }
}
