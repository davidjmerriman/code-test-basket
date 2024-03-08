<?php

namespace CodeTestBasket\Product;

class Product
{
    public function __construct(
        public string $code,
        public string $product,
        public float $price,
    ) {
    }
}
