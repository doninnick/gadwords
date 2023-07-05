<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model\Provider;

use Magento\Framework\Exception\NoSuchEntityException;
use Pronko\GoogleAdWords\Api\TagInterface;
use Pronko\GoogleAdWords\Api\ParametersProviderInterface;
use Magento\Framework\Escaper;
use Pronko\GoogleAdWords\Service\CurrentCategoryProvider;

/**
 * Class Category
 */
class Category implements ParametersProviderInterface
{
    const CURRENT_PAGE = 'category';

    /**
     * @var CurrentCategoryProvider
     */
    private $categoryProvider;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Category constructor.
     * @param CurrentCategoryProvider $categoryProvider
     * @param Escaper $escaper
     */
    public function __construct(
        CurrentCategoryProvider $categoryProvider,
        Escaper $escaper
    ) {
        $this->categoryProvider = $categoryProvider;
        $this->escaper = $escaper;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        try {
            $category = $this->categoryProvider->get();
        } catch (NoSuchEntityException $exception) {
            return [];
        }

        return [
            TagInterface::PARAMETER_NAME_CURRENT_PAGE => self::CURRENT_PAGE,
            TagInterface::PARAMETER_NAME_CATEGORY_NAME => $this->escaper->escapeHtml($category->getName())
        ];
    }
}
