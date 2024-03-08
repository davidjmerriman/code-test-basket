<?php

namespace CodeTestBasket\Tests\Feature;

use CodeTestBasket\Basket\Basket;
use CodeTestBasket\Delivery\TieredDelivery;
use CodeTestBasket\Offer\BuyXGetYPercent;
use CodeTestBasket\Offer\IOffer;
use CodeTestBasket\Offer\IOffersProvider;
use CodeTestBasket\Offer\StaticOffers;
use CodeTestBasket\Product\Product;
use CodeTestBasket\ProductCatalog\StaticCatalog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BasketEndToEndTest extends TestCase
{
    /**
     * @param Product[] $products
     * @param array<int, float> $deliveryTiers
     * @param IOffer[] $offerList
     * @param array<string> $productCodes
     * @param float $expected
     */
    #[DataProvider('dataForEndToEnd')]
    public function testEndToEnd(
        array $products,
        array $deliveryTiers,
        array $offerList,
        array $productCodes,
        float $expected,
    ): void {
        $catalog = new StaticCatalog($products);

        $delivery = new TieredDelivery();
        foreach ($deliveryTiers as $tier => $cost) {
            $delivery->addTier($tier, $cost);
        }

        /** @var IOffersProvider|MockObject */
        $offers = new StaticOffers($offerList);

        $basket = new Basket(
            $catalog,
            $delivery,
            $offers,
        );

        foreach ($productCodes as $code) {
            $basket->add($code);
        }
        $actual = $basket->total();
        $this->assertEqualsWithDelta($expected, $actual, 0.00001);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataForEndToEnd(): array
    {
        $products = [
            new Product('R01', 'Red Widget', 32.95),
            new Product('G01', 'Green Widget', 24.95),
            new Product('B01', 'Red Widget', 7.95),
        ];

        $deliveryTiers = [
            0 => 4.95,
            50 => 2.95,
            90 => 0.00,
        ];

        $offers = [
            new BuyXGetYPercent('R01', 2, 0.5),
        ];

        return [
            'Case 1' => [$products, $deliveryTiers, $offers, ['B01', 'G01'], 37.85],
            'Case 2' => [$products, $deliveryTiers, $offers, ['R01', 'R01'], 54.37],
            'Case 3' => [$products, $deliveryTiers, $offers, ['R01', 'G01'], 60.85],
            'Case 4' => [$products, $deliveryTiers, $offers, ['B01', 'B01', 'R01', 'R01', 'R01'], 98.27],
        ];
    }
}
