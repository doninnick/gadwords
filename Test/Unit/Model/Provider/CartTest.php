<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Test\Unit\Model\Provider;

use Pronko\GoogleAdWords\Model\Provider\Cart;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Model\Product;
use PHPUnit\Framework\TestCase;
use Pronko\GoogleAdWords\Service\CurrentQuoteProvider;

/**
 * Class CartTest
 */
class CartTest extends TestCase
{
    /**
     * @var Cart
     */
    protected $object;

    /**
     * @var Quote | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quote;

    /**
     * @var PriceCurrencyInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $priceCurrency;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $quoteProvider;

    protected function setUp()
    {
        $this->quoteProvider = $this->getMockBuilder(CurrentQuoteProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->priceCurrency = $this->getMockBuilder(PriceCurrencyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quote = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBaseSubtotalWithDiscount', 'getAllVisibleItems'])
            ->getMock();

        $objectManager = new ObjectManagerHelper($this);

        $this->object = $objectManager->getObject(
            Cart::class,
            [
                'priceCurrency' => $this->priceCurrency,
                'quoteProvider' => $this->quoteProvider
            ]
        );
    }

    public function testGetParameters()
    {
        $itemId = 'ABC-1';
        $totalValue = 13.0800;
        $roundedValue = 13.08;

        $expected = [
            'current_page' => 'cart',
            'item_ids' => [$itemId],
            'total_value' => $roundedValue
        ];

        $item = $this->getMockBuilder(Item::class)
            ->disableOriginalConstructor()
            ->getMock();

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quote->expects($this->once())
            ->method('getAllVisibleItems')
            ->willReturn([$item]);

        $this->quote->expects($this->once())
            ->method('getBaseSubtotalWithDiscount')
            ->willReturn($totalValue);

        $this->priceCurrency->expects($this->once())
            ->method('round')
            ->with($totalValue)
            ->willReturn($roundedValue);

        $item->expects($this->once())
            ->method('getProduct')
            ->willReturn($product);

        $product->expects($this->once())
            ->method('getData')
            ->with('sku')
            ->willReturn($itemId);

        $this->quoteProvider->expects($this->any())
            ->method('get')
            ->willReturn($this->quote);

        $result = $this->object->getParameters();
        $this->assertEquals($expected, $result);
    }
}
