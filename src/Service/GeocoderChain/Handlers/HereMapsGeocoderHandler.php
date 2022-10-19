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
		$this->geocoderContext->setGeocoder(new HereMapsGeoCoderStrategy());
		$result = $this->geocoderContext->geocode($address);
		if ($result) {
			$this->saveResolvedAddress($address, $result);
			return $result;
		}
		return parent::handle($address);
	}

}