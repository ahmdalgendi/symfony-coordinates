<?php

use App\Service\Geocoders\GoogleMapsGeocoder;
use App\Service\Geocoders\HereMapsGeoCoder;
use App\ValueObject\Address;

beforeEach(function () {
	$this->hereMapsGeoCoderStrategy = new HereMapsGeoCoder();
});
test('here  returns null if the address is not saved', function () {
	$address = new Address('test', 'test', 'test', 'test');
	$stub =  $this->createMock(HereMapsGeoCoder::class);
	$stub->method('geocode')->willReturn(null);
	$result = $this->hereMapsGeoCoderStrategy->geocode($address);
	expect($result)->toBeNull();

});