<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\Normalizer\CategoryNormalizer;
use Akeneo\Normalizer\EntityNormalizer;
use Akeneo\Normalizer\ProductNormalizer;
use Akeneo\Normalizer\ProductValueNormalizer;

/**
 * Class ClientBuilder
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ClientBuilder
{
    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     *
     * @return AkeneoPimClientInterface
     */
    public function build($baseUri, Authentication $authentication)
    {
        $resourceClient = new ResourceClient($baseUri, $authentication);

        return new AkeneoPimClient($resourceClient);
    }

    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     *
     * @return AkeneoPimObjectClient
     */
    public function buildObjectClient($baseUri, Authentication $authentication)
    {
        $baseClient = $this->build($baseUri, $authentication);

        return new AkeneoPimObjectClient($baseClient, $this->buildEntityNormalizer());
    }

    protected function buildEntityNormalizer()
    {
        $normalizer = new EntityNormalizer();
        $normalizer
            ->registerNormalizer(new CategoryNormalizer())
            ->registerNormalizer(new ProductNormalizer(new ProductValueNormalizer()))
        ;

        return $normalizer;
    }
}
