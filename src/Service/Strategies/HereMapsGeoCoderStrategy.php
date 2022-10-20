<?php

namespace App\Service\Strategies;

use App\Responses\GeocoderResponses\HereMapsGeocoderResponseHandler;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use Throwable;

class HereMapsGeoCoderStrategy implements GeocoderStrategyInterface
{
	const HTTPS_GEOCODE_SEARCH_HEREAPI_COM_V_1_GEOCODE = 'https://geocode.search.hereapi.com/v1/geocode';

	public function __construct()
	{
	}

	public function geocode(Address $address): ?Coordinates
	{
		$apiKey = $_ENV["HEREMAPS_GEOCODING_API_KEY"];

		$params = $this->getParams($address, $apiKey);

		try {
			$data = $this->getHereMapsResponse($params);
		} catch (Throwable $e) {
			return null;
		}

		return HereMapsGeocoderResponseHandler::handleResponse($data);
	}

	/**
	 * @param Address $address
	 * @param $apiKey
	 * @return array[]
	 */
	public function getParams(Address $address, $apiKey): array
	{
		return [
				'query' => [
						'qq' => implode(';', ["country={$address->getCountry()}", "city={$address->getCity()}", "street={$address->getStreet()}",
								"postalCode={$address->getPostcode()}"]),
						'apiKey' => $apiKey,
				],
		];
	}

	/**
	 * @param array $params
	 * @return mixed
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \JsonException
	 */
	public function getHereMapsResponse(array $params)
	{
		$client = new Client();

		$response = $client->get(self::HTTPS_GEOCODE_SEARCH_HEREAPI_COM_V_1_GEOCODE, $params);

		$data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		return $data;
	}
}