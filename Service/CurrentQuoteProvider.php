<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\GoogleAdWords\Service;

use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;

/**
 * Class CurrentQuoteProvider
 */
class CurrentQuoteProvider
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * CurrentOrderProvider constructor.
     * @param Session $checkoutSession
     */
    public function __construct(Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return CartInterface|Quote
     * @throws NoSuchEntityException|LocalizedException
     */
    public function get()
    {
        return $this->checkoutSession->getQuote();
    }
}
