<?php

namespace App\Service\GeocoderChain;

use App\Service\GeocoderChain\Handlers\AbstractClasses\AbstractGeocoderHandler;
use App\Service\GeocoderChain\Handlers\DatabaseHandler;
use App\Service\GeocoderChain\Handlers\GoogleMapsGeocoderHandler;
use App\Service\GeocoderChain\Handlers\HereMapsGeocoderHandler;
use App\Service\Strategies\GeocoderContext;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;

class GeocoderChainService
{
	protected GeocoderContext $geocoderService;
	protected array $handlers = [
			DatabaseHandler::class,
			GoogleMapsGeocoderHandler::class,
			HereMapsGeocoderHandler::class,
	];

	public function __construct(GeocoderContext $geocoderContext)
	{
		$this->geocoderService = $geocoderContext;
	}

	public function geocode(Address $address): ?Coordinates
	{
		return $this->createChainOfResponsibility()->handle($address);
	}

	private function createChainOfResponsibility(): AbstractGeocoderHandler
	{
		if (empty($this->handlers)) {
			throw new \RuntimeException('No handlers found');
		}
		$handlers = [];
		$firsHandler = new $this->handlers[0]($this->geocoderService);
		$handlers[] = $firsHandler;
		for ($i = 1, $iMax = count($this->handlers); $i < $iMax; $i++) {
			$handler = new $this->handlers[$i]($this->geocoderService);
			$handlers[$i - 1]->setNextHandler($handler);
			$handlers[] = $handler;
		}
		return $firsHandler;
	}
}