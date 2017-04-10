<?php

namespace Akeneo\Client;

use Akeneo\Authentication;
use Akeneo\Denormalizer\CategoryDenormalizer;
use Akeneo\Denormalizer\EntityDenormalizer;
use Akeneo\Denormalizer\MediaFileDenormalizer;
use Akeneo\Denormalizer\ProductDenormalizer;
use Akeneo\Normalizer\CategoryNormalizer;
use Akeneo\Normalizer\EntityNormalizer;
use Akeneo\Normalizer\ProductNormalizer;
use Akeneo\Normalizer\ProductValueNormalizer;
use Akeneo\Pagination\Factory\PageFactory;
use Akeneo\Pagination\Factory\PaginatorFactory;

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
        $paginatorFactory = new PaginatorFactory($resourceClient, new PageFactory(new EntityDenormalizer()));

        return new AkeneoPimClient($resourceClient, $paginatorFactory);
    }

    /**
     * @param string         $baseUri
     * @param Authentication $authentication
     *
     * @return AkeneoPimObjectClient
     */
    public function buildObjectClient($baseUri, Authentication $authentication)
    {
        $denormalizer = $this->buildEntityDenormalizer();
        $normalizer =  $this->buildEntityNormalizer();
        $resourceClient = new ResourceClient($baseUri, $authentication);
        $paginatorFactory = new PaginatorFactory($resourceClient, new PageFactory($denormalizer));

        return new AkeneoPimObjectClient($resourceClient, $paginatorFactory, $normalizer, $denormalizer);
    }

    /**
     * @return EntityNormalizer
     */
    protected function buildEntityNormalizer()
    {
        $normalizer = new EntityNormalizer();
        $normalizer
            ->registerNormalizer(new CategoryNormalizer())
            ->registerNormalizer(new ProductNormalizer(new ProductValueNormalizer()))
        ;

        return $normalizer;
    }

    /**
     * @return EntityDenormalizer
     */
    protected function buildEntityDenormalizer()
    {
        $this->entityDernomalizer = new EntityDenormalizer();
        $this->entityDernomalizer
            ->registerDenormalizer(new CategoryDenormalizer())
            ->registerDenormalizer(new ProductDenormalizer())
            ->registerDenormalizer(new MediaFileDenormalizer())
        ;

        return $this->entityDernomalizer;
    }
}
