<?php

namespace Akeneo\Client;

use Akeneo\Authentication;

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
     * @return ClientInterface
     */
    public function build($baseUri, Authentication $authentication)
    {
        //$container = new ContainerBuilder();
        //$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config'));
        //$loader->load('config.yml');

        $resourceClient = new ResourceClient($baseUri, $authentication);

        return new AkeneoPimClient($resourceClient);
    }
}
