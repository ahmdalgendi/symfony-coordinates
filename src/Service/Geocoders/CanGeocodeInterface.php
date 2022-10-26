<?php

declare(strict_types=1);

namespace App\Service\Geocoders;

use App\ValueObject\Address;
use App\ValueObject\Coordinates;

interface CanGeocodeInterface
{
    public function geocode(Address $address): ?Coordinates;
}
