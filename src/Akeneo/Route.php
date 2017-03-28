<?php

namespace Akeneo;

/**
 * Routes of the API
 *
 * @author    Alexandre Hocquard <alexandre.hocquard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Route
{
    const TOKEN = 'api/oauth/v1/token';

    const ATTRIBUTE = 'api/rest/v1/attributes/%s';
    const ATTRIBUTES = 'api/rest/v1/attributes';

    const ATTRIBUTE_OPTION = 'api/rest/v1/attributes/%s/options/%s';
    const CHANNEL = 'api/rest/v1/channels/%s';
    const FAMILY = 'api/rest/v1/families/%s';
    const LOCALE = 'api/rest/v1/locales/%s';

    const CATEGORY = 'api/rest/v1/categories/%s';
    const CATEGORIES = 'api/rest/v1/categories';

    const PRODUCT = 'api/rest/v1/products/%s';
    const PRODUCTS = 'api/rest/v1/products';
}
