<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\GoogleAdWords\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CurrentProductProvider
 */
class CurrentProductProvider
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * CurrentProductProvider constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param RequestInterface $request
     */
    public function __construct(ProductRepositoryInterface $productRepository, RequestInterface $request)
    {
        $this->productRepository = $productRepository;
        $this->request = $request;
    }

    /**
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function get()
    {
        return $this->productRepository->getById($this->request->getParam('id'));
    }
}
