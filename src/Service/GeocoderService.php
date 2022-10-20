<?php

namespace App\Service;

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
		$coordinates = $this->geocoderChainService->geocode($address);
		return $coordinates && $coordinates->isValid() ? $coordinates : null;
	}
}