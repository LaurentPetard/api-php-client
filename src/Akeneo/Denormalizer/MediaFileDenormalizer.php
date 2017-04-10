<?php

namespace Akeneo\Denormalizer;

use Akeneo\Entities\MediaFile;

/**
 * Class MediaFileDenormalizer
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MediaFileDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type)
    {
        return new MediaFile(
            $data['code'],
            $data['original_filename'],
            $data['mime_type'],
            $data['size'],
            $data['extension'],
            $data['_links']['download']['href']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($type)
    {
        return MediaFile::class === $type;
    }
}
