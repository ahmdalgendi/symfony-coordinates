<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Strategies\GeocoderInterface;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderService
{
	private GeocoderInterface $geocoder;


	public function geocode(Address $address): ?Coordinates
	{
		return $this->geocoder->geocode($address);
	}

	public function setGeocoder(GeocoderInterface $geocoder): void
	{
		$this->geocoder = $geocoder;
	}
}