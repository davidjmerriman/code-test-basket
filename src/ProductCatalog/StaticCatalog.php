<?php

namespace CodeTestBasket\ProductCatalog;

use CodeTestBasket\Product\Product;

class StaticCatalog implements IProductCatalogProvider
{
    /** @var array<string, Product> */
    private array $products = [];

    /**
     * @param Product[] $products
     */
    public function __construct(array $products)
    {
        // Manually reinserting these into the product array to ensure
        // no two products share a code
        /** @var Product $product */
        foreach ($products as $product) {
            $this->products[$product->code] = $product;
        }
    }

    public function get(string $productCode): Product|null
    {
        return $this->products[$productCode] ?? null;
    }

    public function list(): array
    {
        return $this->products;
    }
}
