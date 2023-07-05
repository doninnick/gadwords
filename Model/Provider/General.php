<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model\Provider;

use Pronko\GoogleAdWords\Api\ParametersProviderInterface;
use Pronko\GoogleAdWords\Api\TagInterface;

/**
 * Class General
 */
class General implements ParametersProviderInterface
{
    /**
     * @var string
     */
    private $currentPage;

    /**
     * General constructor.
     * @param string $currentPage
     */
    public function __construct($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return [
            TagInterface::PARAMETER_NAME_CURRENT_PAGE => $this->currentPage
        ];
    }
}
