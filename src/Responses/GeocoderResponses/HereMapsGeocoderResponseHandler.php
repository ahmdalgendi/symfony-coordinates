<?php

namespace App\Responses\GeocoderResponses;

use App\ValueObject\Coordinates;

class HereMapsGeocoderResponseHandler implements GeocoderResponseHandler
{
	public static function handleResponse(array $response): ?Coordinates
	{
		if (self::invalidAddress($response)) {
			return null;
		}
		$firstItem = $response['items'][0];

		return new Coordinates(
				$firstItem['position']['lat'],
				$firstItem['position']['lng']
		);
	}

	private static function invalidAddress($response): bool
	{
		return empty($response['items']) ||
		       $response['items'][0]['resultType'] !== 'houseNumber';
	}
}