<?php

namespace Akeneo\Entities;

/**
 * Class MediaFile
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MediaFile
{
    /** @var string */
    protected $code;

    /** @var string */
    protected $originalFileName;

    /** @var string */
    protected $mimeType;

    /** @var int */
    protected $size;

    /** @var string */
    protected $extension;

    /** @var string */
    protected $downloadLink;

    public function __construct($code, $originalFileName, $mimeType, $size, $extension, $downloadLink)
    {
        $this->code =$code;
        $this->originalFileName = $originalFileName;
        $this->mimeType = $mimeType;
        $this->size = (int) $size;
        $this->extension= $extension;
        $this->downloadLink = $downloadLink;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getDownloadLink()
    {
        return $this->downloadLink;
    }
}
