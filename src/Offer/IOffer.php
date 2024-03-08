<?php

namespace CodeTestBasket\Offer;

use CodeTestBasket\ProductCatalog\IProductCatalogProvider;

interface IOffer
{
    /**
     * Calculates the discount this offer generates for the selected line items
     *
     * @param array<string, int> $lineItems The line items we are checking for a discount
     */
    public function discount(array $lineItems, IProductCatalogProvider $catalog): float;
}
