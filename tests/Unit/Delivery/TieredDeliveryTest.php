<?php

namespace CodeTestBasket\Tests\Unit\Delivery;

use CodeTestBasket\Delivery\TieredDelivery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TieredDeliveryTest extends TestCase
{
    /**
     * @param array<int, float> $tiersToAdd
     * @param float $subtotal
     * @param array<string, int> $lineItems
     * @param float $expectedCost
     */
    #[DataProvider('dataForDeliveryCost')]
    public function testDeliveryCost(array $tiersToAdd, float $subtotal, array $lineItems, float $expectedCost): void
    {
        $tieredDelivery = new TieredDelivery();
        foreach ($tiersToAdd as $tier => $cost) {
            $tieredDelivery->addTier($tier, $cost);
        }
        $actualCost = $tieredDelivery->deliveryCost($subtotal, $lineItems);
        $this->assertEquals($expectedCost, $actualCost);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataForDeliveryCost(): array
    {
        return [
            'No tiers' => [[], 100, [], 0],
            '3 tier low' => [[0 => 4.95, 50 => 2.95, 90 => 0], 1, [], 4.95],
            '3 tier low bound' => [[0 => 4.95, 50 => 2.95, 90 => 0], 0, [], 4.95],
            '3 tier mid' => [[0 => 4.95, 50 => 2.95, 90 => 0], 60, [], 2.95],
            '3 tier mid bound' => [[0 => 4.95, 50 => 2.95, 90 => 0], 50, [], 2.95],
            '3 tier high' => [[0 => 4.95, 50 => 2.95, 90 => 0], 100, [], 0],
            '3 tier high bound' => [[0 => 4.95, 50 => 2.95, 90 => 0], 90, [], 0],
        ];
    }

    /**
     * @param array<int, float> $tiersToAdd
     * @param array<int, float> $expectedList
     */
    #[DataProvider('dataForAddTier')]
    public function testAddTier(array $tiersToAdd, array $expectedList): void
    {
        $tieredDelivery = new TieredDelivery();
        foreach ($tiersToAdd as $tier => $cost) {
            $tieredDelivery->addTier($tier, $cost);
        }
        $actualList = $tieredDelivery->listTiers();
        $this->assertEquals($expectedList, $actualList);
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataForAddTier(): array
    {
        return [
            'No tiers' => [[], []],
            'One tier' => [[0 => 5.95], [0 => 5.95]],
            'Two tier' => [[0 => 5.95, 100 => 0], [0 => 5.95, 10000 => 0]],
            'Reversed' => [[100 => 0, 0 => 5.95], [0 => 5.95, 10000 => 0]],
        ];
    }
}
