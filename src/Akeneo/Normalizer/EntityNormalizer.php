<?php

namespace Akeneo\Normalizer;


/**
 * Class EntityNormalizer
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class EntityNormalizer implements NormalizerInterface
{
    /**
     * @var array
     */
    protected $normalizers = [];

    public function normalize($object)
    {
        $normalizer = $this->getSupportedNormalizer($object);

        if (!$normalizer instanceof NormalizerInterface) {
            throw new \Exception('The normalization of this object is not supported');
        }

        return $normalizer->normalize($object);
    }

    public function supportsNormalization($object)
    {
        $normalizer = $this->getSupportedNormalizer($object);

        return $normalizer instanceof NormalizerInterface;
    }

    public function registerNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;

        return $this;
    }

    protected function getSupportedNormalizer($object)
    {
        foreach ($this->normalizers as $normalizer) {
            if (true === $normalizer->supportsNormalization($object)) {
                return $normalizer;
            }
        }

        return null;
    }
}
