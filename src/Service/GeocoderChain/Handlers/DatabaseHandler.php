<?php

namespace App\Service\GeocoderChain\Handlers;

use App\Service\GeocoderChain\Handlers\AbstractClasses\AbstractGeocoderHandler;
use App\Service\Strategies\DatabaseGeocoderStrategyStrategy;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class DatabaseHandler extends AbstractGeocoderHandler
{
	protected DatabaseGeocoderStrategyStrategy $databaseGeocoderStrategy;

	public function handle(Address $address): ?Coordinates
	{
		$this->databaseGeocoderStrategy = new DatabaseGeocoderStrategyStrategy($this->geocoderContext->resolvedAddressRepository);
		$result = $this->databaseGeocoderStrategy->geocode($address);
		return $result ?? parent::handle($address);
	}
}