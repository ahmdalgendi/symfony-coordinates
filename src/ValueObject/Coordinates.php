<?php

declare(strict_types=1);

namespace App\ValueObject;

class Coordinates
{
	const LAT_MIN = -90;
	const LAT_MAX = 90;
	const LNG_MIN = -180;
	const LNG_MAX = 180;
	private float $lat;
	private float $lng;

	public function __construct(float $lat, float $lng)
	{
		$this->lat = $lat;
		$this->lng = $lng;
	}

	public function getLat(): float
	{
		return $this->lat;
	}

	public function getLng(): float
	{
		return $this->lng;
	}

	public function isValid(): bool
	{
		return $this->lat > self::LAT_MIN &&
		       $this->lat < self::LAT_MAX &&
		       $this->lng > self::LNG_MIN &&
		       $this->lng < self::LNG_MAX;
	}
}
