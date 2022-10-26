<?php

use App\Service\Geocoders\GoogleMapsGeocoder;
use App\ValueObject\Address;

beforeEach(function () {
	$this->googleMapsGeocoderStrategy = new GoogleMapsGeocoder();
});
test('google  returns null if the address is not saved', function () {
	$address = new Address('test', 'test', 'test', 'test');
	$stub =  $this->createMock(GoogleMapsGeocoder::class);
	$stub->method('geocode')->willReturn(null);

	$result = $this->googleMapsGeocoderStrategy->geocode($address);
	expect($result)->toBeNull();

});