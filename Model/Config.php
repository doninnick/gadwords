<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Pronko\GoogleAdWords\Service\CurrentOrderProvider;

/**
 * Class Config
 */
class Config
{
    /**#@+
     * Configuration paths
     */
    private const XML_PATH_ACTIVE = 'pronko/dynamic_remarketing/active';
    private const XML_PATH_TAG = 'pronko/dynamic_remarketing/tag';
    private const XML_PATH_CONVERSION_ID = 'pronko/dynamic_remarketing/conversion_id';
    private const XML_PATH_CONVERSION_LANGUAGE = 'pronko/dynamic_remarketing/conversion_language';
    private const XML_PATH_CONVERSION_FORMAT = 'pronko/dynamic_remarketing/conversion_format';
    private const XML_PATH_CONVERSION_COLOR = 'pronko/dynamic_remarketing/conversion_color';
    private const XML_PATH_CONVERSION_LABEL = 'pronko/dynamic_remarketing/conversion_label';
    private const XML_PATH_CONVERSION_JS_SRC = 'pronko/dynamic_remarketing/conversion_js_src';
    private const XML_PATH_CONVERSION_IMG_SRC = 'pronko/dynamic_remarketing/conversion_img_src';
    private const XML_PATH_SEND_CURRENCY = 'pronko/dynamic_remarketing/send_currency';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CurrentOrderProvider
     */
    private $orderProvider;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $storeConfig
     */
    public function __construct(
        ScopeConfigInterface $storeConfig,
        CurrentOrderProvider $orderProvider
    ) {
        $this->scopeConfig = $storeConfig;
        $this->orderProvider = $orderProvider;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_TAG,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get conversion path to js src
     *
     * @return string
     */
    public function getConversionJsSrc(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_CONVERSION_JS_SRC);
    }

    /**
     * Get conversion img src
     *
     * @return string
     */
    public function getConversionImgSrc(): string
    {
        return sprintf(
            $this->scopeConfig->getValue(self::XML_PATH_CONVERSION_IMG_SRC),
            $this->getConversionId(),
            $this->getConversionLabel()
        );
    }

    /**
     * Get Google AdWords conversion id
     *
     * @return int
     */
    public function getConversionId(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_CONVERSION_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Google AdWords conversion language
     *
     * @return string
     */
    public function getConversionLanguage(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONVERSION_LANGUAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Google AdWords conversion format
     *
     * @return int
     */
    public function getConversionFormat(): int
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONVERSION_FORMAT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Google AdWords conversion color
     *
     * @return string
     */
    public function getConversionColor(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONVERSION_COLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Google AdWords conversion label
     *
     * @return string
     */
    public function getConversionLabel(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONVERSION_LABEL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Google AdWords conversion value
     *
     * @return float
     */
    public function getConversionValue(): float
    {
        if ($this->isSendOrderCurrency()) {
            $conversionValue = round($this->orderProvider->get()->getGrandTotal(), 2);
        } else {
            $conversionValue = round($this->orderProvider->get()->getBaseGrandTotal(), 2);
        }
        return $conversionValue;
    }

    /**
     * Get send order currency to Google Adwords
     *
     * @return boolean
     */
    public function isSendOrderCurrency(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SEND_CURRENCY,
            ScopeInterface::SCOPE_STORE
        );
    }
}
