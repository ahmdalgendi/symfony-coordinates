<?php

declare(strict_types=1);

namespace App\Service\Strategies;

use App\Repository\ResolvedAddressRepository;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderContext
{
	private GeocoderStrategyInterface $geocoder;
	public ResolvedAddressRepository $resolvedAddressRepository;

	public function __construct(ResolvedAddressRepository $resolvedAddressRepository)
	{
		$this->resolvedAddressRepository = $resolvedAddressRepository;
	}

	public function geocode(Address $address): ?Coordinates
	{
		return $this->geocoder->geocode($address);
	}

	public function setGeocoder(GeocoderStrategyInterface $geocoder): void
	{
		$this->geocoder = $geocoder;
	}
}