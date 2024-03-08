<?php

namespace CodeTestBasket\Delivery;

class TieredDelivery implements IDeliveryProvider
{
    private array $tiers = [];

    public function deliveryCost(float $subtotal, array $lineItems): float
    {
        $deliveryCost = 0;
        foreach ($this->tiers as $tier => $cost) {
            if ($tier > $subtotal) break;
            $deliveryCost = $cost;
        }

        return $deliveryCost;
    }

    /**
     * Adds a delivery pricing tier, with its tier value and cost
     *
     * @param float $tier The level above which this tier applies
     * @param float $cost The delivery cost for orders of this tier
     * @return self
     */
    public function addTier(float $tier, float $cost): self
    {
        $this->tiers[$tier] = $cost;
        ksort($this->tiers, SORT_NUMERIC);
        return $this;
    }

    /**
     * Lists the tiers configured for this delivery provider
     *
     * @return array The array of tiered pricing
     */
    public function listTiers(): array
    {
        return $this->tiers;
    }
}
