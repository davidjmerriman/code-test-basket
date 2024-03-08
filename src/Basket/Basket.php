<?php

namespace CodeTestBasket\Basket;

use CodeTestBasket\Delivery\IDeliveryProvider;
use CodeTestBasket\Offer\IOffersProvider;
use CodeTestBasket\ProductCatalog\IProductCatalogProvider;

class Basket
{
    /** @var array<string, int> */
    private array $lineItems = [];

    public function __construct(
        private IProductCatalogProvider $catalog,
        private IDeliveryProvider $delivery,
        private IOffersProvider $offers,
    ) {
    }

    /**
     * Adds a product, identified by its code, to the basket
     *
     * @param string $productCode The code of the product to be added
     * @return int The current number of $productCode items in the basket
     */
    public function add(string $productCode): int
    {
        if ($this->catalog->get($productCode)) {
            $this->lineItems[$productCode] = ($this->lineItems[$productCode] ?? 0) + 1;
        }
        return $this->lineItems[$productCode] ?? 0;
    }

    /**
     * Calculates the total cost of purchase the basket in its current state
     *
     * @return float The cost of good, less applicable offers, plus delivery costs
     */
    public function total(): float
    {
        $total = 0;

        // Total up the line items in the basket
        foreach ($this->lineItems as $productCode => $quantity) {
            $product = $this->catalog->get($productCode);
            if (!$product) {
                continue;
            }
            $total += round($quantity * $product->price, 2);
        }

        // Apply any applicable offers
        foreach ($this->offers->list() as $offer) {
            $total -= $offer->discount($this->lineItems, $this->catalog);
        }

        // Calculate and add in the delivery cost
        $total += $this->delivery->deliveryCost($total, $this->lineItems);

        return $total;
    }
}
