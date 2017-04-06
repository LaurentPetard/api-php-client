<?php

namespace Akeneo\Entities;

/**
 * Class Label
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Label
{
    /** @var string */
    protected $locale;

    /** @var string */
    protected $value;

    /**
     * @param string $locale
     * @param string $value
     */
    public function __construct($locale, $value)
    {
        $this->locale = $locale;
        $this->value = $value;
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
    public function getValue()
    {
        return $this->value;
    }
}
