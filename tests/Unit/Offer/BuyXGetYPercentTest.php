<?php

namespace CodeTestBasket\Tests\Unit\Offer;

use CodeTestBasket\Offer\BuyXGetYPercent;
use CodeTestBasket\Product\Product;
use CodeTestBasket\ProductCatalog\IProductCatalogProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BuyXGetYPercentTest extends TestCase
{
    /**
     * @param string $code
     * @param int $quantity
     * @param float $percent
     * @param array<string, int> $lineItems
     * @param float $expected
     */
    #[DataProvider('dataForDiscount')]
    public function testDiscount(
        string $code,
        int $quantity,
        float $percent,
        array $lineItems,
        float $expected,
    ): void {
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

        $offer = new BuyXGetYPercent($code, $quantity, $percent);
        $actual = $offer->discount($lineItems, $catalog);
        $this->assertEqualsWithDelta($expected, $actual, 0.00001);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataForDiscount(): array
    {
        return [
            'No code match' => ['R01', 2, 1.0, ['B01' => 2], 0],
            'Insufficient items' => ['R01', 2, 1.0, ['R01' => 1], 0],
            'Discount applied' => ['R01', 2, 1.0, ['R01' => 2], 10],
        ];
    }
}
