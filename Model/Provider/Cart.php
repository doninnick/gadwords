<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model\Provider;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Pronko\GoogleAdWords\Api\TagInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Pronko\GoogleAdWords\Api\ParametersProviderInterface;
use Pronko\GoogleAdWords\Service\CurrentQuoteProvider;

/**
 * Class Cart
 */
class Cart implements ParametersProviderInterface
{
    /**
     * Current page value
     */
    const CURRENT_PAGE = 'cart';

    /**
     * @var CurrentQuoteProvider
     */
    private $quoteProvider;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * Cart constructor.
     * @param CurrentQuoteProvider $quoteProvider
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        CurrentQuoteProvider $quoteProvider,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->quoteProvider = $quoteProvider;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        try {
            return [
                TagInterface::PARAMETER_NAME_CURRENT_PAGE => self::CURRENT_PAGE,
                TagInterface::PARAMETER_NAME_ITEM_IDS => $this->getItemIds(),
                TagInterface::PARAMETER_NAME_TOTAL_VALUE => $this->getTotalValue()
            ];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return array
     * @throws LocalizedException|NoSuchEntityException
     */
    private function getItemIds(): array
    {
        $result = [];
        foreach ($this->quoteProvider->get()->getAllVisibleItems() as $item) {
            $sku = $item->getData('sku');
            $result[$sku] = $sku;
        }
        return array_values($result);
    }

    /**
     * @return float
     * @throws LocalizedException|NoSuchEntityException
     */
    private function getTotalValue()
    {
        return $this->priceCurrency->round($this->quoteProvider->get()->getBaseSubtotalWithDiscount());
    }
}
