<?php
/**
 * Copyright © Pronko Consulting (https://www.pronkoconsulting.com)
 * See LICENSE for the license details.
 */

namespace Pronko\GoogleAdWords\Test\Unit\Model\Provider;

use Magento\Framework\Exception\NoSuchEntityException;
use Pronko\GoogleAdWords\Model\Provider\Product;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Pronko\GoogleAdWords\Service\CurrentProductProvider;

/**
 * Class ProductTest
 */
class ProductTest extends TestCase
{
    /**
     * @var Product
     */
    private $object;

    /**
     * @var CurrentProductProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productProvider;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->productProvider = $this->getMockBuilder(CurrentProductProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $this->object = $objectManager->getObject(
            Product::class,
            [
                'productProvider' => $this->productProvider
            ]
        );
    }

    public function testGetParametersNoProduct()
    {
        $this->productProvider->expects($this->any())
            ->method('get')
            ->willThrowException(new NoSuchEntityException());
        $result = $this->object->getParameters();
        $this->assertEmpty($result);
    }

    public function testGetParametersSimpleProduct()
    {
        $itemId = 'sku';
        $roundedValue = 12.09;
        $expected = [
            'current_page' => 'product',
            'item_ids' => $itemId,
            'total_value' => $roundedValue
        ];

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFinalPrice', 'getSku'])
            ->getMock();

        $product->expects($this->once())
            ->method('getFinalPrice')
            ->willReturn($roundedValue);
        $product->expects($this->once())
            ->method('getSku')
            ->willReturn($itemId);

        $this->productProvider->expects($this->any())
            ->method('get')
            ->willReturn($product);

        $result = $this->object->getParameters();
        $this->assertEquals($expected, $result);
    }
}
