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
	protected GeocoderContext $geocoderContext;
	protected array $handlers = [
			DatabaseHandler::class,
			GoogleMapsGeocoderHandler::class,
			HereMapsGeocoderHandler::class,
	];

	public function __construct(GeocoderContext $geocoderContext)
	{
		$this->geocoderContext = $geocoderContext;
	}

	public function geocode(Address $address): ?Coordinates
	{
		$coordinates = $this->createChainOfResponsibility()->handle($address);
		if ($coordinates === null) {
			$this->geocoderContext->resolvedAddressRepository->saveResolvedAddress($address, $coordinates);
		}
		return $coordinates;
	}

	private function createChainOfResponsibility(): AbstractGeocoderHandler
	{
		if (empty($this->handlers)) {
			throw new \RuntimeException('No handlers found');
		}
		$handlers = [];
		$firsHandler = new $this->handlers[0]($this->geocoderContext);
		$handlers[] = $firsHandler;
		for ($i = 1, $iMax = count($this->handlers); $i < $iMax; $i++) {
			// check that I am extending AbstractGeocoderHandler
			if(!is_subclass_of($this->handlers[$i], AbstractGeocoderHandler::class)) {
				throw new \RuntimeException('Handler must extend AbstractGeocoderHandler');
			}
			$handler = new $this->handlers[$i]($this->geocoderContext);
			$handlers[$i - 1]->setNextHandler($handler);
			$handlers[] = $handler;
		}
		return $firsHandler;
	}

	public function addHandler(string $handler): void
	{
		//check if the class name extends AbstractGeocoderHandler
		if(!is_subclass_of($handler, AbstractGeocoderHandler::class)) {
			throw new \RuntimeException('Handler must extend AbstractGeocoderHandler');
		}
		if (!in_array($handler, $this->handlers, true)) {
			$this->handlers[] = $handler;
		}
	}
}