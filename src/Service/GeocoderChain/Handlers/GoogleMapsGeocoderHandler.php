<?php

namespace App\Service\GeocoderChain\Handlers;

use App\Service\GeocoderChain\Handlers\AbstractClasses\AbstractGeocoderHandler;
use App\Service\Strategies\GoogleMapsGeocoderStrategyStrategy;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GoogleMapsGeocoderHandler extends AbstractGeocoderHandler
{
	public function handle(Address $address): ?Coordinates
	{
		$this->geocoderContext->setGeocoder(new GoogleMapsGeocoderStrategyStrategy());
		$result = $this->geocoderContext->geocode($address);
		if ($result) {
			$this->geocoderContext->resolvedAddressRepository->saveResolvedAddress($address, $result);
		}
		return $result ?? parent::handle($address);
	}
}