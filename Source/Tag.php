<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Pronko\GoogleAdWords\Api\TagInterface;
use Pronko\GoogleAdWords\Model\TagPool;

/**
 * Class Tag
 */
class Tag implements OptionSourceInterface
{
    /**
     * @var TagInterface[]
     */
    private $tagPool;

    /**
     * Tag constructor.
     * @param TagPool $tagPool
     */
    public function __construct(TagPool $tagPool)
    {
        $this->tagPool = $tagPool;
    }

    /**
     * Return option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];
        foreach ($this->tagPool->getTagsCodes() as $code) {
            $result[] = [
                'value' => $code,
                'label' => ucfirst($code)
            ];
        }
        return $result;
    }
}
