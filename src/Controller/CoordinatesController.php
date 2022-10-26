<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\InvalidGeocoderClass;
use App\Service\GeocoderService;
use App\Service\Geocoders\GoogleMapsGeocoder;
use App\Service\Geocoders\HereMapsGeoCoder;
use App\ValueObject\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends AbstractController
{
	/**
	 * @Route(path="/coordinates", name="geocode")
	 * @param Request $request
	 * @return Response
	 * @throws InvalidGeocoderClass
	 */
	public function geocodeAction(Request $request, GeocoderService $service): Response
	{
		$country = $request->get('countryCode', 'lt');
		$city = $request->get('city', 'vilnius');
		$street = $request->get('street', 'jasinskio 16');
		$postcode = $request->get('postcode', '01112');

		$address = new Address($country, $city, $street, $postcode);

		$coordinates = $service->geocode($address);

		if (null === $coordinates) {
			return new JsonResponse([], Response::HTTP_NOT_FOUND);
		}
		return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }

    /**
     * @Route(path="/gmaps", name="gmaps")
     * @param Request $request
     * @return Response
     */
	public function gmapsAction(Request $request, GoogleMapsGeocoder $googleMapsGeocoder): Response
	{
		$country = $request->get('country', 'lithuania');
		$city = $request->get('city', 'vilnius');
		$street = $request->get('street', 'jasinskio 16');
		$postcode = $request->get('postcode', '01112');

		$address = new Address($country, $city, $street, $postcode);

		$coordinates = $googleMapsGeocoder->geocode($address);
		if (null === $coordinates) {
			return new JsonResponse([], Response::HTTP_NOT_FOUND);
		}
		return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
	}

	/**
	 * @Route(path="/hmaps", name="hmaps")
	 * @param Request $request
	 * @return Response
	 */
	public function hmapsAction(Request $request, HereMapsGeoCoder $hereMapsGeoCoder): Response
	{
		$country = $request->get('country', 'lithuania');
		$city = $request->get('city', 'vilnius');
		$street = $request->get('street', 'jasinskio 16');
		$postcode = $request->get('postcode', '01112');
		$address = new Address($country, $city, $street, $postcode);

		$coordinates = $hereMapsGeoCoder->geocode($address);
		if (null === $coordinates) {
			return new JsonResponse([], Response::HTTP_NOT_FOUND);
		}
		return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
	}
}