<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\GoogleAdWords\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\HTTP\PhpEnvironment\ServerAddress;
use Exception;

/**
 * Class Extension
 */
class Extension
{
    /**
     * @var ClientFactory $clientFactory
     */
    private $clientFactory;
    /**
     * @var ServerAddress
     */
    private $serverAddress;
    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var string
     */
    private $url = 'https://www.pronkoconsulting.com/extension/analytics/index';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * Extension constructor.
     * @param ClientFactory $clientFactory
     * @param ServerAddress $serverAddress
     * @param ModuleDataSetupInterface $setup
     * @param SerializerInterface $serializer
     * @param ScopeConfigInterface $scopeConfig
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        ClientFactory $clientFactory,
        ServerAddress $serverAddress,
        ModuleDataSetupInterface $setup,
        SerializerInterface $serializer,
        ScopeConfigInterface $scopeConfig,
        ProductMetadataInterface $productMetadata
    ) {
        $this->clientFactory = $clientFactory;
        $this->serverAddress = $serverAddress;
        $this->setup = $setup;
        $this->serializer = $serializer;
        $this->scopeConfig = $scopeConfig;
        $this->productMetadata = $productMetadata;
    }
    /**
     * @return void
     */
    public function execute()
    {
        $select = $this->setup->getConnection()->select()
            ->from($this->setup->getConnection()->getTableName('core_config_data'))
            ->where('path LIKE ?', 'pronko/dynamic_remarketing/active')
            ->where('path LIKE ?', 'pronko/dynamic_remarketing/tag')
            ->orWhere('path LIKE ?', 'web/unsecure/base_url');
        $result = $this->setup->getConnection()->fetchAll($select);
        $content = [];
        foreach ($result as $item) {
            $key = sprintf('%s-%s-%s', $item['scope'], $item['scope_id'], $item['path']);
            $content[$key] = $item['value'];
        }
        $this->save(array_merge($content, $this->readConfig()));
    }
    /**
     * @return array
     */
    private function readConfig(): array
    {
        $config = [];
        foreach ([
                     'pronko/dynamic_remarketing/active',
                     'pronko/dynamic_remarketing/tag',
                 ] as $path) {
            $config[$path] = $this->scopeConfig->getValue($path);
        }
        return $config;
    }
    /**
     * @param array $content
     */
    private function save(array $content)
    {
        $content['remote-ip'] = $this->serverAddress->getServerAddress();
        $content['edition'] = $this->getEdition();
        $content['timestamp'] = time();
        $url = $this->url . '?' . http_build_query([
                'query' => base64_encode($this->serializer->serialize($content))
            ]);
        try {
            $client = $this->clientFactory->create();
            $client->setTimeout(5);
            $client->get($url);
        } catch (Exception $exception) {
            unset($content, $url);
        }
    }
    /**
     * @return string
     */
    private function getEdition()
    {
        return $this->productMetadata->getName()
            . ' ' . $this->productMetadata->getVersion()
            . ' ' . $this->productMetadata->getEdition();
    }
}
