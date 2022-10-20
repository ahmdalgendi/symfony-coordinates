<?php

namespace App\Service\Strategies;

use App\Responses\GeocoderResponses\HereMapsGeocoderResponseHandler;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class HereMapsGeoCoderStrategy implements GeocoderStrategyInterface
{
	const HTTPS_GEOCODE_SEARCH_HEREAPI_COM_V_1_GEOCODE = 'https://geocode.search.hereapi.com/v1/geocode';

	public function __construct()
	{
	}

	public function geocode(Address $address): ?Coordinates
	{
		$apiKey = $_ENV["HEREMAPS_GEOCODING_API_KEY"];

		$params = [
				'query' => [
						'qq' => implode(';', ["country={$address->getCountry()}", "city={$address->getCity()}", "street={$address->getStreet()}",
								"postalCode={$address->getPostcode()}"]),
						'apiKey' => $apiKey
				]
		];

		$client = new Client();

		$response = $client->get(self::HTTPS_GEOCODE_SEARCH_HEREAPI_COM_V_1_GEOCODE, $params);

		$data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

		return HereMapsGeocoderResponseHandler::handleResponse($data);
	}
}