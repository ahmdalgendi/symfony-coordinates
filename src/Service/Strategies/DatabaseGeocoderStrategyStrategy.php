<?php

namespace App\Service\Strategies;

use App\Repository\ResolvedAddressRepository;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class DatabaseGeocoderStrategyStrategy implements GeocoderStrategyInterface
{
	private ResolvedAddressRepository $resolvedAddressRepository;
	public function __construct(ResolvedAddressRepository $resolvedAddressRepository)
	{
		$this->resolvedAddressRepository = $resolvedAddressRepository;
	}

	public function geocode(Address $address): ?Coordinates
	{
		$coordinates = $this->resolvedAddressRepository->getByAddress($address);
		if (null === $coordinates) {
			return null;
		}
		if ($coordinates->getLng() === null || $coordinates->getLat() === null) {
			// return coordinates with invalid values
			return new Coordinates(-200, -200);
		}

		return new Coordinates($coordinates->getLat(), $coordinates->getLng());
	}
}