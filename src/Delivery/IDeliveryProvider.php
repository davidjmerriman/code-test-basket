<?php

namespace CodeTestBasket\Delivery;

interface IDeliveryProvider
{
    /**
     * Calculates the delivery cost of a order from the subtotal and/or the line items in the order
     */
    public function deliveryCost(float $subtotal, array $lineItems): float;
}
