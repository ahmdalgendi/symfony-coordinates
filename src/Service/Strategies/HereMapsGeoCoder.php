<?php

namespace App\Service\Strategies;

use App\Responses\GeocoderResponses\HereMapsGeocoderResponseHandler;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class HereMapsGeoCoder implements GeocoderInterface
{
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

		$response = $client->get('https://geocode.search.hereapi.com/v1/geocode', $params);

		$data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

		return HereMapsGeocoderResponseHandler::handleResponse($data);
	}
}