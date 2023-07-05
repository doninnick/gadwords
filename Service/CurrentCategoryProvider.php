<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\GoogleAdWords\Service;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\Data\CategoryInterface;

/**
 * Class CurrentCategoryProvider
 */
class CurrentCategoryProvider
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * CurrentCategoryProvider constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param RequestInterface $request
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        RequestInterface $request
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->request = $request;
    }

    /**
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    public function get()
    {
        return $this->categoryRepository->get($this->request->getParam('id'));
    }
}
