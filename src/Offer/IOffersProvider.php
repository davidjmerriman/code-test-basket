<?php

namespace CodeTestBasket\Offer;

interface IOffersProvider
{
    /**
     * Lists all offers available
     *
     * @return IOffer[]
     */
    public function list(): array;
}
