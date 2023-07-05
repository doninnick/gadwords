<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Block;

use Magento\Framework\Serialize\SerializerInterface;
use Pronko\GoogleAdWords\Model\Config;
use Magento\Framework\View\Element\Template;
use Pronko\GoogleAdWords\Model\TagPool;
use Pronko\GoogleAdWords\Api\ParametersProviderInterface;

/**
 * Class Script
 */
class Script extends Template
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var TagPool
     */
    private $tagPool;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Script constructor.
     * @param Template\Context $context
     * @param Config $config
     * @param TagPool $tagPool
     * @param SerializerInterface $serializer
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        TagPool $tagPool,
        SerializerInterface $serializer,
        array $data = []
    ) {
        $this->config = $config;
        $this->tagPool = $tagPool;
        $this->serializer = $serializer;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return $this->config->isActive() ? parent::_toHtml() : '';
    }

    /**
     * @return int
     */
    public function getConversionId()
    {
        return $this->config->getConversionId();
    }

    /**
     * @return int
     */
    public function getConversionLabel()
    {
        return $this->config->getConversionLabel();
    }

    /**
     * @return string
     */
    public function getRemarketingOnly()
    {
        return $this->getData('remarketingOnly') ? 'true' : 'false';
    }

    /**
     * @return bool
     */
    public function renderConversion()
    {
        return !$this->getData('remarketingOnly');
    }

    /**
     * @return string
     */
    public function getConversionImgSrc()
    {
        return $this->config->getConversionImgSrc();
    }

    /**
     * @return string
     */
    public function getConversionJsSrc()
    {
        return $this->config->getConversionJsSrc();
    }

    /**
     * @return array
     */
    public function getCustomParameters()
    {
        $provider = $this->getData('parametersProvider');

        if ($provider instanceof ParametersProviderInterface) {
            return $this->tagPool->get($this->config->getTag())
                ->convert($provider->getParameters());
        }
        return [];
    }

    /**
     * @return bool|string
     */
    public function getCustomParametersJson()
    {
        return $this->serializer->serialize($this->getCustomParameters());
    }

    /**
     * @return string
     */
    public function getOrderCurrencyCode()
    {
        return $this->_storeManager->getStore()->getDefaultCurrencyCode();
    }

    /**
     * @return string
     */
    public function getConversionLanguage()
    {
        return $this->config->getConversionLanguage();
    }

    /**
     * @return string
     */
    public function getConversionFormat()
    {
        return $this->config->getConversionFormat();
    }

    /**
     * @return string
     */
    public function getConversionColor()
    {
        return $this->config->getConversionColor();
    }

    /**
     * @return string
     */
    public function getConversionValue()
    {
        return $this->config->getConversionValue();
    }
}
