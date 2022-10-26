<?php

namespace App\Factories;

use App\Exceptions\InvalidGeocoderClass;
use App\Service\Geocoders\CanGeocodeInterface;
use App\Service\Geocoders\DatabaseGeocoder;
use App\Service\Geocoders\GoogleMapsGeocoder;
use App\Service\Geocoders\HereMapsGeoCoder;

class GeocoderFactory
{
	private HereMapsGeoCoder $hereMapsGeoCoder;
	private DatabaseGeocoder $databaseGeocoder;
	private GoogleMapsGeocoder $googleMapsGeocoder;

	public function __construct(
			DatabaseGeocoder   $databaseGeocoder,
			GoogleMapsGeocoder $googleMapsGeocoder,
			HereMapsGeoCoder   $hereMapsGeoCoder
	)
	{
		$this->databaseGeocoder = $databaseGeocoder;
		$this->googleMapsGeocoder = $googleMapsGeocoder;
		$this->hereMapsGeoCoder = $hereMapsGeoCoder;
	}

	/**
	 * @throws InvalidGeocoderClass
	 */
	public function getGeocoder(string $className): CanGeocodeInterface
	{
		switch ($className) {
			case DatabaseGeocoder::class:
				return $this->databaseGeocoder;
			case GoogleMapsGeocoder::class:
				return $this->googleMapsGeocoder;
			case HereMapsGeoCoder::class:
				return $this->hereMapsGeoCoder;
			default:
				throw new InvalidGeocoderClass('Geocoder not found');
		}
	}
}