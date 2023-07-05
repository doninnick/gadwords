<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Test\Unit\Model\Provider;

use Magento\Sales\Api\Data\OrderItemInterface;
use Pronko\GoogleAdWords\Model\Provider\Purchase;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Sales\Model\Order;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;
use Pronko\GoogleAdWords\Service\CurrentOrderProvider;

/**
 * Class PurchaseTest
 */
class PurchaseTest extends TestCase
{
    /**
     * @var Purchase
     */
    protected $object;

    /**
     * @var CurrentOrderProvider | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $orderProvider;

    /**
     * @var PriceCurrencyInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceCurrency;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->orderProvider = $this->getMockBuilder(CurrentOrderProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->priceCurrency = $this->getMockBuilder(PriceCurrencyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = $objectManager->getObject(
            Purchase::class,
            [
                'orderProvider' => $this->orderProvider,
                'priceCurrency' => $this->priceCurrency
            ]
        );
    }

    public function testGetParameters()
    {
        $itemId = 'ABC-1';
        $baseSubtotal = 10.0800;
        $roundedValue = 10.08;
        $expected = [
            'current_page' => 'purchase',
            'item_ids' => [$itemId],
            'total_value' => $roundedValue
        ];

        $item = $this->getMockForAbstractClass(
            OrderItemInterface::class,
            [],
            '',
            false,
            false,
            true,
            ['getProduct']
        );

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $order = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $order->expects($this->once())
            ->method('getAllVisibleItems')
            ->willReturn([$item]);
        $order->expects($this->once())
            ->method('getBaseSubtotal')
            ->willReturn($baseSubtotal);

        $this->orderProvider->expects($this->any())
            ->method('get')
            ->willReturn($order);

        $this->priceCurrency->expects($this->once())
            ->method('round')
            ->with($baseSubtotal)
            ->willReturn($roundedValue);

        $item->expects($this->once())
            ->method('getProduct')
            ->willReturn($product);

        $product->expects($this->once())
            ->method('getData')
            ->with('sku')
            ->willReturn($itemId);

        $result = $this->object->getParameters();
        $this->assertEquals($expected, $result);
    }
}
