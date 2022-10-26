# My approach to solve the problem 

![Flow chart](https://i.imgur.com/1hh2cal.png)

## Solution Overview
1. created a geocode service where should the logic go
2. created a constant array to hold the correct order to run the geocoders in
3. created a factory class that returns an instance of the correct geocoder 
4. loop over the array and call the geocoder in the correct order
5. if we get a result from the geocoder we return it
6. if not we continue to the next geocoder
7. save the resolved address that has no coordinates again in the service
8. return the resolved coordinates to the controller
9. if the resolved coordinates are not null, then return the 404 to the user

### Inside any geocoder
- call geocode to get the coordinates
- save the result to the database

## Files structure

### Service Directory
-  GeocoderService.php -> `the class that implements the geocode method and returns the resolved coordinates`
-  Geocoders\ -> `directory that holds the geocoders classes`
   - CanGeocodeInterface.php -> `interface that all geocoders should implement`
   - DatabaseGeocoder.php -> `geocoder that gets the address from the database`
   - GoogleMapsGeocoder.php -> `geocoder that gets the address from google maps`
   - HereMapsGeoCoder.php -> `geocoder that gets the address from here maps`
### Factories Directory
- Directory that holds the factories of the geocoders
   - GeocoderFactory.php -> `factory that returns an instance of the correct geocoder`
### Responses Directory
- Directory that holds the responses of the service
   - GeocoderResponses\ -> `directory that holds the responses of the geocoders`
      - GeocoderResponse.php -> `interface that all geocoders responses should implement`
      - DatabaseGeocoderResponse.php -> `response of the database geocoder`
      - GoogleMapsGeocoderResponse.php -> `response of the google maps geocoder`
      
## How to run the test cases 
``
./vendor/bin/pest
``

## How to add new geocoders?
1. create a new class that implements the CanGeocodeInterface
2. add the new class to the GeocoderFactory
3. add the new class to the geocoders array in the GeocoderService in the correct order
4. Thats it

## Cases that should be handled as well
1. we should not call the $_ENV inside the services and should be moved to a config file
2. should log all the exception to be easier to debug them and find out the root cause
3. should implement request validation to make sure that the request is valid