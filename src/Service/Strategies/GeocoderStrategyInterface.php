<?php

declare(strict_types=1);

namespace App\Service\Strategies;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

interface GeocoderStrategyInterface
{
    public function geocode(Address $address): ?Coordinates;
}
