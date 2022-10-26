<?php

namespace App\Service\Geocoders;

use App\Exceptions\InvalidHereMapHttpException;
use App\Repository\ResolvedAddressRepository;
use App\Responses\GeocoderResponses\HereMapsGeocoderResponseHandler;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use JsonException;
use Throwable;

class HereMapsGeoCoder implements CanGeocodeInterface
{
	const HTTPS_GEOCODE_SEARCH_HEREAPI_COM_V_1_GEOCODE = 'https://geocode.search.hereapi.com/v1/geocode';
	
	public function geocode(Address $address): ?Coordinates
	{
		$params = $this->getParams($address);

		try {
			$data = $this->getHereMapsResponse($params);
		} catch (InvalidHereMapHttpException|Throwable $e) {
			#todo: log the exception cause
			return null;
		}
		return HereMapsGeocoderResponseHandler::handleResponse($data);
	}

	/**
	 * @param Address $address
	 * @return array[]
	 */
	public function getParams(Address $address): array
	{
		#todo: should be moved to a config file
		$apiKey = $_ENV["HEREMAPS_GEOCODING_API_KEY"];
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
	 * @throws InvalidHereMapHttpException
	 * @throws JsonException
	 */
	public function getHereMapsResponse(array $params)
	{
		$client = new Client();

		$response = $client->get(self::HTTPS_GEOCODE_SEARCH_HEREAPI_COM_V_1_GEOCODE, $params);

		return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
	}
}