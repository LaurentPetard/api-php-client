<?php

namespace Akeneo\Pagination\Factory;

use Akeneo\Denormalizer\DenormalizerInterface;
use Akeneo\Pagination\Page;

/**
 * Class PageFactory
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class PageFactory implements PageFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createPage(array $data, $pageNumber)
    {
        $nextLink = isset($data['_links']['next']['href']) ? $data['_links']['next']['href'] : null;
        $previousLink = isset($data['_links']['previous']['href']) ? $data['_links']['previous']['href'] : null;
        $selfLink = $data['_links']['self']['href'];
        $firstLink = $data['_links']['first']['href'];
        $items = $data['_embedded']['items'];

        return new Page($selfLink, $firstLink, $previousLink, $nextLink, $pageNumber, $items);
    }
}
