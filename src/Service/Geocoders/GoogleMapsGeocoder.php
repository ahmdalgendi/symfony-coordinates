<?php

declare(strict_types=1);

namespace App\Service\Geocoders;

use App\Exceptions\InvalidGoogleMapHttpException;
use App\Repository\ResolvedAddressRepository;
use App\Responses\GeocoderResponses\GoogleMapsGeocoderResponseHandler;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use GuzzleHttp\Client;
use JsonException;
use Throwable;

class GoogleMapsGeocoder implements CanGeocodeInterface
{
	public const HTTPS_MAPS_GOOGLEAPIS_COM_MAPS_API_GEOCODE_JSON = 'https://maps.googleapis.com/maps/api/geocode/json';
	private ResolvedAddressRepository $resolvedAddressRepository;

	public function __construct(ResolvedAddressRepository $resolvedAddressRepository)
	{
		$this->resolvedAddressRepository = $resolvedAddressRepository;
	}

	public function geocode(Address $address): ?Coordinates
	{
		$params = $this->getParams($address);
		try {
			$data = $this->getGoogleGeocodeResponse($params);
		} catch (InvalidGoogleMapHttpException|Throwable $e) {
			#todo: log the exception cause
			return null;
		}
		return GoogleMapsGeocoderResponseHandler::handleResponse($data);
	}

	/**
	 * @param Address $address
	 * @return array[]
	 */
	public function getParams(Address $address): array
	{
		#todo: should be moved to a config file
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
	 * @throws InvalidGoogleMapHttpException
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
