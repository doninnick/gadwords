<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model;

use Pronko\GoogleAdWords\Api\TagInterface;

/**
 * Class Tag
 */
class Tag implements TagInterface
{
    /**
     * @var array
     */
    private $parametersMapping;

    /**
     * Retail constructor.
     * @param array $parametersMapping
     */
    public function __construct(
        array $parametersMapping
    ) {
        $this->parametersMapping = $parametersMapping;
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function convert(array $parameters)
    {
        $result = [];

        foreach ($this->parametersMapping as $from => $to) {
            if (isset($parameters[$from])) {
                $result[$to] = $parameters[$from];
            }
        }

        return $result;
    }
}
