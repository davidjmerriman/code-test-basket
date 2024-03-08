<?php

namespace CodeTestBasket\Tests\Unit\Basket;

use CodeTestBasket\Basket\Basket;
use CodeTestBasket\Delivery\IDeliveryProvider;
use CodeTestBasket\Offer\IOffer;
use CodeTestBasket\Offer\IOffersProvider;
use CodeTestBasket\Product\Product;
use CodeTestBasket\ProductCatalog\IProductCatalogProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    public function testAdd(): void
    {
        $red = new Product('R01', 'Red', 10);
        $blue = new Product('B01', 'Blue', 20);

        /** @var IProductCatalogProvider|MockObject $catalog */
        $catalog = $this->getMockBuilder(IProductCatalogProvider::class)->getMock();
        $catalog->expects($this->any())->method('get')->willReturnCallback(
            fn ($code) => match ($code) {
                'R01' => $red,
                'B01' => $blue,
                default => null,
            }
        );

        /** @var IDeliveryProvider|MockObject */
        $delivery = $this->getMockBuilder(IDeliveryProvider::class)->getMock();
        $delivery->method('deliveryCost')->willReturnCallback(fn ($subtotal) => $subtotal >= 50 ? 0 : 5);

        /** @var IOffer|MockObject */
        $offer = $this->getMockBuilder(IOffer::class)->getMock();
        $offer->method('discount')->willReturnCallback(
            fn ($lineItems, $catalog) => ($lineItems['R01'] ?? 0) >= 2 ? 3 : 0
        );

        /** @var IOffersProvider|MockObject */
        $offers = $this->getMockBuilder(IOffersProvider::class)->getMock();
        $offers->method('list')->willReturn([$offer]);

        $basket = new Basket($catalog, $delivery, $offers);

        $codes = ['R01', 'B01', 'R01', 'D01'];
        $counts = [1, 1, 2, 0];

        foreach ($codes as $index => $code) {
            $actualCount = $basket->add($code);
            $this->assertEquals($counts[$index], $actualCount);
        }
    }

    /**
     * @param array<string> $codes
     * @param float $expected
     */
    #[DataProvider('dataForTotal')]
    public function testTotal(array $codes, float $expected): void
    {
        $red = new Product('R01', 'Red', 10);
        $blue = new Product('B01', 'Blue', 20);

        /** @var IProductCatalogProvider|MockObject $catalog */
        $catalog = $this->getMockBuilder(IProductCatalogProvider::class)->getMock();
        $catalog->expects($this->any())->method('get')->willReturnCallback(
            fn ($code) => match ($code) {
                'R01' => $red,
                'B01' => $blue,
                default => null,
            }
        );

        /** @var IDeliveryProvider|MockObject */
        $delivery = $this->getMockBuilder(IDeliveryProvider::class)->getMock();
        $delivery->method('deliveryCost')->willReturnCallback(fn ($subtotal) => $subtotal >= 50 ? 0 : 5);

        /** @var IOffer|MockObject */
        $offer = $this->getMockBuilder(IOffer::class)->getMock();
        $offer->method('discount')->willReturnCallback(
            fn ($lineItems, $catalog) => ($lineItems['R01'] ?? 0) >= 2 ? 3 : 0
        );

        /** @var IOffersProvider|MockObject */
        $offers = $this->getMockBuilder(IOffersProvider::class)->getMock();
        $offers->method('list')->willReturn([$offer]);

        $basket = new Basket($catalog, $delivery, $offers);

        foreach ($codes as $code) {
            $basket->add($code);
        }

        $actual = $basket->total();
        $this->assertEqualsWithDelta($expected, $actual, 0.00001);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataForTotal(): array
    {
        return [
            'Nothing' => [[], 5],
            'R01 x1' => [['R01'], 15],
            'R01 x2' => [['R01', 'R01'], 22],
            'R01 x3 B01 x1' => [['R01', 'R01', 'R01', 'B01'], 52],
            'R01 x3 B01 x1 G01 x2' => [['R01', 'R01', 'R01', 'B01', 'G01', 'G01'], 52],
        ];
    }
}
