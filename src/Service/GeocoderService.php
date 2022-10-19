<?php

namespace App\Service;

use App\Repository\ResolvedAddressRepository;
use App\Service\GeocoderChain\GeocoderChainService;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderService
{
	protected GeocoderChainService $geocoderChainService;
	private ResolvedAddressRepository $resolvedAddressRepository;

	public function __construct(GeocoderChainService $geocoderChainService, ResolvedAddressRepository $resolvedAddressRepository)
	{
		$this->geocoderChainService = $geocoderChainService;
		$this->resolvedAddressRepository = $resolvedAddressRepository;
	}

	public function geocode(Address $address): ?Coordinates
	{
		$coordinates = $this->geocoderChainService->geocode($address);
		$this->resolvedAddressRepository->saveResolvedAddress($address, $coordinates);
		return $coordinates;
	}
}