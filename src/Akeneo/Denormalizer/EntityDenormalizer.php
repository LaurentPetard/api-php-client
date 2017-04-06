<?php

namespace Akeneo\Denormalizer;

/**
 * Class EntityDenormalizer
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class EntityDenormalizer implements DenormalizerInterface
{
    /**
     * @var array
     */
    protected $denormalizers = [];

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type)
    {
        $denormalizer = $this->getSupportedDenormalizer($type);

        if (! $denormalizer instanceof DenormalizerInterface) {
            throw new \Exception('The denormalization of this type of data is not supported');
        }

        return $denormalizer->denormalize($data, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($type)
    {
        $denormalizer = $this->getSupportedDenormalizer($type);

        return $denormalizer instanceof DenormalizerInterface;
    }

    /**
     * @param DenormalizerInterface $denormalizer
     *
     * @return EntityDenormalizer
     */
    public function registerDenormalizer(DenormalizerInterface $denormalizer)
    {
        $this->denormalizers[] = $denormalizer;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return DenormalizerInterface|null
     */
    protected function getSupportedDenormalizer($type)
    {
        foreach ($this->denormalizers as $denormalizer) {
            if (true === $denormalizer->supportsNormalization($type)) {
                return $denormalizer;
            }
        }

        return null;
    }
}
