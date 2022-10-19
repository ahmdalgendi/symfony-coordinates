<?php

namespace App\Service\GeocoderChain\Handlers\AbstractClasses;

use App\Service\Strategies\GeocoderContext;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

abstract class AbstractGeocoderHandler
{
	protected GeocoderContext $geocoderService;

	public function __construct(GeocoderContext $geocoderService)
	{
		$this->geocoderService = $geocoderService;
	}

	protected ?AbstractGeocoderHandler $nextHandler = null;

	public function setNextHandler(AbstractGeocoderHandler $handler): AbstractGeocoderHandler
	{
		$this->nextHandler = $handler;

		return $handler;
	}

	public function handle(Address $address): ?Coordinates
	{
		if (null !== $this->nextHandler) {
			return $this->nextHandler->handle($address);
		}
		return null;
	}
}