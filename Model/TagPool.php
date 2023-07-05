<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Model;

use Pronko\GoogleAdWords\Api\TagInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class TagPool
 */
class TagPool
{
    /**
     * @var TagInterface[]
     */
    private $tags;

    /**
     * @param array $tags
     */
    public function __construct(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTagsCodes()
    {
        return array_keys($this->tags);
    }

    /**
     * Retrieves operation
     *
     * @param string $tagCode
     * @return TagInterface
     * @throws NotFoundException
     */
    public function get($tagCode)
    {
        if (!isset($this->tags[$tagCode])) {
            throw new NotFoundException(__('Tag %1 does not exist.', $tagCode));
        }

        return $this->tags[$tagCode];
    }
}
