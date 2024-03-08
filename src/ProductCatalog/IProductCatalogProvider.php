<?php

namespace CodeTestBasket\ProductCatalog;

use CodeTestBasket\Product\Product;

interface IProductCatalogProvider
{
    /**
     * Gets a single Product by code, or null if the product is not found
     *
     * @param string $code The product code to look up
     * @return Product The product found with the code, or null if none is found
     */
    public function get(string $code): Product|null;

    /**
     * Lists all Products in the catalog
     *
     * @return Product[]
     */
    public function list(): array;
}
