<?php

namespace CodeTestBasket\Offer;

use CodeTestBasket\ProductCatalog\IProductCatalogProvider;

class BuyXGetYPercent implements IOffer
{
    public function __construct(
        private string $code,
        private int $quantity,
        private float $percent,
    ) {
    }

    public function discount(array $lineItems, IProductCatalogProvider $catalog): float
    {
        if (($lineItems[$this->code] ?? 0) < $this->quantity) {
            return 0;
        }

        $product = $catalog->get($this->code);
        return round($product?->price * $this->percent, 2);
    }
}
