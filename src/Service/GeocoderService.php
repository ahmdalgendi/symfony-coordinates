<?php

namespace App\Service;

use App\Repository\ResolvedAddressRepository;
use App\Service\GeocoderChain\GeocoderChainService;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderService
{
	protected GeocoderChainService $geocoderChainService;

	public function __construct(GeocoderChainService $geocoderChainService)
	{
		$this->geocoderChainService = $geocoderChainService;
	}

	public function geocode(Address $address): ?Coordinates
	{
		return $this->geocoderChainService->geocode($address);
	}
}