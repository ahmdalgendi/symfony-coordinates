<?php

namespace App\Responses\GeocoderResponses;

use App\ValueObject\Coordinates;

class GoogleMapsGeocoderResponseHandler implements GeocoderResponseHandler
{
	public static function handleResponse(array $response): ?Coordinates
	{
		if (empty($response['results'])) {
			return null;
		}

		$result = $response['results'][0];
		if(self::invalidAddress($result)) {
			return null;
		}
		return new Coordinates(
			$result['geometry']['location']['lat'],
			$result['geometry']['location']['lng']
		);
	}

	private static function invalidAddress($result): bool
	{
		return empty($result) || $result['geometry']['location_type'] !== 'ROOFTOP';
	}
}