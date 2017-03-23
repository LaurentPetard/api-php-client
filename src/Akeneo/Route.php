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

    const GET_ATTRIBUTE = 'api/rest/v1/attributes/%s';
    const GET_ATTRIBUTE_OPTION = 'api/rest/v1/attributes/%s/options/%s';
    const GET_CHANNEL = 'api/rest/v1/channels/%s';
    const GET_FAMILY = 'api/rest/v1/families/%s';
    const GET_LOCALE = 'api/rest/v1/locales/%s';

    const GET_CATEGORY = 'api/rest/v1/categories/%s';
    const GET_CATEGORIES = 'api/rest/v1/categories';
}
