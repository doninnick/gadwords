<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Api;

/**
 * Interface TagInterface
 * @api
 */
interface TagInterface
{
    const PARAMETER_NAME_CURRENT_PAGE = 'current_page';
    const PARAMETER_NAME_ITEM_IDS = 'item_ids';
    const PARAMETER_NAME_TOTAL_VALUE = 'total_value';
    const PARAMETER_NAME_CATEGORY_NAME = 'category_name';

    /**
     * @param array $parameters
     * @return array
     */
    public function convert(array $parameters);
}
