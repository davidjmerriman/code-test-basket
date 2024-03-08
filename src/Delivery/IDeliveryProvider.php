<?php

namespace CodeTestBasket\Delivery;

interface IDeliveryProvider
{
    /**
     * Calculates the delivery cost of a basket from the subtotal and/or the line items in the basket
     *
     * @param float $subtotal The current subtotal of the basket
     * @param array<string, int> $lineItems The actual line items in the basket
     * @return float The cost of delivery for this basket
     */
    public function deliveryCost(float $subtotal, array $lineItems): float;
}
