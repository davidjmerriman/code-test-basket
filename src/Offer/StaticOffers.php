<?php

namespace CodeTestBasket\Offer;

class StaticOffers implements IOffersProvider
{
    /**
     * @param IOffer[] $offers The list of offers for this offer provider
     */
    public function __construct(
        private array $offers
    ) {
    }

    public function list(): array
    {
        return $this->offers;
    }
}
