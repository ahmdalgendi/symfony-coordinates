<?php

namespace App\Service\GeocoderChain\Handlers;

use App\Service\GeocoderChain\Handlers\AbstractClasses\AbstractGeocoderHandler;
use App\Service\Strategies\GeocoderStrategyInterface;
use App\Service\Strategies\GoogleMapsGeocoderStrategyStrategy;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GoogleMapsGeocoderHandler extends AbstractGeocoderHandler
{
	public function handle(Address $address): ?Coordinates
	{
		$this->geocoderService->setGeocoder(new GoogleMapsGeocoderStrategyStrategy());
		$result = $this->geocoderService->geocode($address);
		return $result ?? parent::handle($address);
	}
}