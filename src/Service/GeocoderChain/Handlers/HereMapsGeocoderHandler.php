<?php

namespace App\Service\GeocoderChain\Handlers;

use App\Service\GeocoderChain\Handlers\AbstractClasses\AbstractGeocoderHandler;
use App\Service\Strategies\HereMapsGeoCoderStrategy;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class HereMapsGeocoderHandler extends AbstractGeocoderHandler
{
	public function handle(Address $address): ?Coordinates
	{
		$this->geocoderService->setGeocoder(new HereMapsGeoCoderStrategy());
		$result = $this->geocoderService->geocode($address);
		return $result ?? parent::handle($address);
	}

}