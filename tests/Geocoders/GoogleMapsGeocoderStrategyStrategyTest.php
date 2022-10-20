<?php

use App\Service\Strategies\GoogleMapsGeocoderStrategyStrategy;
use App\ValueObject\Address;

beforeEach(function () {
	$this->googleMapsGeocoderStrategy = new GoogleMapsGeocoderStrategyStrategy();
});
test('google  returns null if the address is not saved', function () {
	$address = new Address('test', 'test', 'test', 'test');
	$stub =  $this->createMock(GoogleMapsGeocoderStrategyStrategy::class);
	$stub->method('geocode')->willReturn(null);

	$result = $this->googleMapsGeocoderStrategy->geocode($address);
	expect($result)->toBeNull();

});