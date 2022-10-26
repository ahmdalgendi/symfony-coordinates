<?php

namespace App\Service;

use App\Exceptions\InvalidGeocoderClass;
use App\Factories\GeocoderFactory;
use App\Repository\ResolvedAddressRepository;
use App\Service\Geocoders\DatabaseGeocoder;
use App\Service\Geocoders\Geocoder;
use App\Service\Geocoders\GoogleMapsGeocoder;
use App\Service\Geocoders\HereMapsGeoCoder;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderService
{
	private GeocoderFactory $geocoderFactory;
	private ResolvedAddressRepository $resolvedAddressRepository;
	public const strategiesOrder = [
			DatabaseGeocoder::class,
			GoogleMapsGeocoder::class,
			HereMapsGeoCoder::class,
	];

	public function __construct(GeocoderFactory $geocoderFactory, ResolvedAddressRepository $resolvedAddressRepository)
	{
		$this->geocoderFactory = $geocoderFactory;
		$this->resolvedAddressRepository = $resolvedAddressRepository;
	}

	/**
	 * @throws InvalidGeocoderClass
	 */
	public function geocode(Address $address): ?Coordinates
	{
		$coordinates = null;
		$geocoderStrategy = null;
		//loop through strategies in order
		foreach (self::strategiesOrder as $strategy) {
			$geocoder = $this->geocoderFactory->getGeocoder($strategy);

			$coordinates = $geocoder->geocode($address);
			if (null !== $coordinates) {
				$geocoderStrategy = $strategy;
				break;
			}
		}
		$this->saveResolvedAddress($address, $coordinates, $geocoderStrategy);
		return $this->getCoordinatesResponse($coordinates);
	}

	private function saveResolvedAddress(Address $address, ?Coordinates $coordinates, ?string
	$strategy): void
	{
		if ($coordinates === null || $strategy !== DatabaseGeocoder::class) {
			$this->resolvedAddressRepository->saveResolvedAddress($address, $coordinates);
		}
	}

	/**
	 * @param Coordinates|null $coordinates
	 * @return Coordinates|null
	 */
	public function getCoordinatesResponse(?Coordinates $coordinates): ?Coordinates
	{
		if ($coordinates === null) {
			return null;
		}
		return $coordinates->isValid() ? $coordinates : null;
	}
}