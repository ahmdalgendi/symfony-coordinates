<?php
namespace App\Responses\GeocoderResponses;

use App\ValueObject\Coordinates;

interface GeocoderResponseHandler
{
	public static function handleResponse(array $response): ?Coordinates;
}