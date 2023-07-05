<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model\Provider;

use Magento\Framework\Exception\NoSuchEntityException;
use Pronko\GoogleAdWords\Api\TagInterface;
use Pronko\GoogleAdWords\Api\ParametersProviderInterface;
use Pronko\GoogleAdWords\Service\CurrentProductProvider;

/**
 * Class Product
 */
class Product implements ParametersProviderInterface
{
    const CURRENT_PAGE = 'product';

    /**
     * @var CurrentProductProvider
     */
    private $productProvider;

    /**
     * Product constructor.
     * @param CurrentProductProvider $productProvider
     */
    public function __construct(CurrentProductProvider $productProvider)
    {
        $this->productProvider = $productProvider;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        try {
            $product = $this->productProvider->get();
        } catch (NoSuchEntityException $exception) {
            return [];
        }

        return [
            TagInterface::PARAMETER_NAME_CURRENT_PAGE => self::CURRENT_PAGE,
            TagInterface::PARAMETER_NAME_ITEM_IDS => $product->getSku(),
            TagInterface::PARAMETER_NAME_TOTAL_VALUE => $product->getFinalPrice()
        ];
    }
}
