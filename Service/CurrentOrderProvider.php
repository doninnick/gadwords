<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */
declare(strict_types=1);

namespace Pronko\GoogleAdWords\Service;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use Magento\Checkout\Model\Session;

/**
 * Class CurrentOrderProvider
 */
class CurrentOrderProvider
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
     * @return OrderInterface|Order
     */
    public function get()
    {
        return $this->checkoutSession->getLastRealOrder();
    }
}
