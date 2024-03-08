<?php

namespace CodeTestBasket\Tests\Unit\ProductCatalog;

use CodeTestBasket\Product\Product;
use CodeTestBasket\ProductCatalog\StaticCatalog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StaticCatalogTest extends TestCase
{
    // public function testGet(array $products, string $productCode, Product|null $expected): void
    // {

    // }

    /**
     * @param Product[] $products
     * @param Product[] $expected
     */
    #[DataProvider('dataForList')]
    public function testList(array $products, array $expected): void
    {
        $catalog = new StaticCatalog($products);
        $actual = $catalog->list();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataForList(): array
    {
        return [
            'No products' => [[], []],
            'One product' => [
                [new Product('R01', 'Red Widget', 32.95)],
                ['R01' => new Product('R01', 'Red Widget', 32.95)]
            ],
            'Two products' => [
                [
                    new Product('R01', 'Red Widget', 32.95),
                    new Product('G01', 'Green Widget', 24.95)
                ],
                [
                    'R01' => new Product('R01', 'Red Widget', 32.95),
                    'G01' => new Product('G01', 'Green Widget', 24.95)
                ],
            ],
            'List with duplicates' => [
                [
                    new Product('R01', 'Red Widget', 32.95),
                    new Product('G01', 'Green Widget', 24.95),
                    new Product('R01', 'Duplicate', 4.95)
                ],
                [
                    'R01' => new Product('R01', 'Duplicate', 4.95),
                    'G01' => new Product('G01', 'Green Widget', 24.95)
                ],
            ],
        ];
    }
}
