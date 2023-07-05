<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model\Provider;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Pronko\GoogleAdWords\Api\TagInterface;
use Pronko\GoogleAdWords\Api\ParametersProviderInterface;
use Pronko\GoogleAdWords\Service\CurrentOrderProvider;

/**
 * Class Purchase
 */
class Purchase implements ParametersProviderInterface
{
    /**
     * Current page name
     */
    const CURRENT_PAGE = 'purchase';

    /**
     * @var CurrentOrderProvider
     */
    private $orderProvider;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * Purchase constructor.
     * @param CurrentOrderProvider $orderProvider
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        CurrentOrderProvider $orderProvider,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->orderProvider = $orderProvider;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return [
            TagInterface::PARAMETER_NAME_CURRENT_PAGE => self::CURRENT_PAGE,
            TagInterface::PARAMETER_NAME_ITEM_IDS => $this->getItemIds(),
            TagInterface::PARAMETER_NAME_TOTAL_VALUE => $this->getTotalValue()
        ];
    }

    /**
     * @return array
     */
    private function getItemIds()
    {
        $result = [];
        /** @var OrderItemInterface $item */
        foreach ($this->orderProvider->get()->getAllVisibleItems() as $item) {
            $sku = $item->getProduct()->getData('sku');
            $result[$sku] = $sku;
        }

        return array_values($result);
    }

    /**
     * @return float
     */
    private function getTotalValue()
    {
        return round($this->orderProvider->get()->getBaseGrandTotal(), 2);
    }
}
