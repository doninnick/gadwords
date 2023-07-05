<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Api;

use Magento\Framework\Data\CollectionDataSourceInterface;

/**
 * Interface ParameterProviderInterface
 * @api
 */
interface ParametersProviderInterface extends CollectionDataSourceInterface
{
    /**
     * @return array
     */
    public function getParameters();
}
