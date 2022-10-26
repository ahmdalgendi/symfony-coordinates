<?php

namespace App\Service\Geocoders;

use App\Repository\ResolvedAddressRepository;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class DatabaseGeocoder implements CanGeocodeInterface
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