<?php

use App\Service\Strategies\GoogleMapsGeocoderStrategyStrategy;
use App\Service\Strategies\HereMapsGeoCoderStrategy;
use App\ValueObject\Address;

beforeEach(function () {
	$this->hereMapsGeoCoderStrategy = new HereMapsGeoCoderStrategy();
});
test('here  returns null if the address is not saved', function () {
	$address = new Address('test', 'test', 'test', 'test');
	$stub =  $this->createMock(HereMapsGeoCoderStrategy::class);
	$stub->method('geocode')->willReturn(null);
	$result = $this->hereMapsGeoCoderStrategy->geocode($address);
	expect($result)->toBeNull();

});