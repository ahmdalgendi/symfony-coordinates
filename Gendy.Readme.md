# My approach to solve the problem 

The solution is divided into three parts:

## Strategies
  we have 3 strategies to fetch the geocode of any address
    
1. fetch from the database
2. Google Geocode API
3. Here Geocode API

so I created an interface to implement the strategy pattern and created an context class to use the strategy

I used the context class inside the 2 actions 
- hmapsAction
- gmapsAction

## Chain of Responsibility
Here is the solution to the actual problem that we need to keep checking whether we can get the geocode from the database or from the APIs
I created a chain and a handler for each strategy then added the handlers in the needed order

## Controller
- update the code in the controller to use the geocoder service


## How to extend the solution if we want to add more ways to geocode
1. create a stratefy class that implements the GeocodeStrategy interface
2. create a response handler class that implements the GeocodeResponseHandler interface
3. create a new chain handler class that extends the AbstractGeocoderHandler class
4. add the new class to the handlers array inside GeocoderChainService class or add the new handler using the method add handler, the handler must extend the AbstractGeocoderHandler class

## How to run the test cases 

``
./vendor/bin/pest
``


## Why I used the strategy pattern and did not implement the handlers directly ?

- I noticed that you the current code created 2 methods one for hereMaps and another for GoogleMaps, so I thought that you might want to add more ways to geocode in the future, so I used the strategy pattern to make it easy to add more ways to geocode