<?php

declare(strict_types=1);

namespace App\Service\Strategies;

use App\Responses\GeocoderResponses\GoogleMapsGeocoderResponseHandler;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class GoogleMapsGeocoder implements GeocoderInterface
{
	public const HTTPS_MAPS_GOOGLEAPIS_COM_MAPS_API_GEOCODE_JSON = 'https://maps.googleapis.com/maps/api/geocode/json';

	/**
	 * @throws GuzzleException
	 * @throws JsonException
	 */
	public function geocode(Address $address): ?Coordinates
	{
		$params = $this->getParams($address);
		$data = $this->getGoogleGeocodeResponse($params);

		return GoogleMapsGeocoderResponseHandler::handleResponse($data);
	}

	/**
	 * @param Address $address
	 * @return array[]
	 */
	public function getParams(Address $address): array
	{
		$apiKey = $_ENV["GOOGLE_GEOCODING_API_KEY"];
		return [
				'query' => [
						'address' => $address->getStreet(),
						'components' => implode('|', [
								"country:{$address->getCountry()}",
								"locality:{$address->getCity()}",
								"postal_code:{$address->getPostcode()}",
						]),
						'key' => $apiKey,
				],
		];
	}

	/**
	 * @param array $params
	 * @return mixed
	 * @throws GuzzleException
	 * @throws JsonException
	 */
	public function getGoogleGeocodeResponse(array $params)
	{
		$client = new Client();

		$response = $client->get(self::HTTPS_MAPS_GOOGLEAPIS_COM_MAPS_API_GEOCODE_JSON, $params);

		$data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		return $data;
	}
}
