<?php

declare(strict_types=1);

namespace App\Service;

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
		$apiKey = $_ENV["GOOGLE_GEOCODING_API_KEY"];
		$params = [
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
		$client = new Client();

		$response = $client->get(self::HTTPS_MAPS_GOOGLEAPIS_COM_MAPS_API_GEOCODE_JSON, $params);

		$data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		if (count($data['results']) === 0) {
			return null;
		}

		$firstResult = $data['results'][0];

		if ($firstResult['geometry']['location_type'] !== 'ROOFTOP') {
			return null;
		}

		return new Coordinates(
				$firstResult['geometry']['location']['lat'],
				$firstResult['geometry']['location']['lng']
		);
	}
}
