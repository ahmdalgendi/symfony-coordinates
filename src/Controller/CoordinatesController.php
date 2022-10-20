<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GeocoderService;
use App\Service\Strategies\GeocoderContext;
use App\Service\Strategies\GoogleMapsGeocoderStrategyStrategy;
use App\Service\Strategies\HereMapsGeoCoderStrategy;
use App\ValueObject\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoordinatesController extends AbstractController
{
	private GeocoderContext $geocoderService;

	public function __construct(GeocoderContext $geocoderService)
	{
		$this->geocoderService = $geocoderService;
	}

	/**
	 * @Route(path="/coordinates", name="geocode")
	 * @param Request $request
	 * @return Response
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
			return new JsonResponse([]);
		}
		return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }

    /**
     * @Route(path="/gmaps", name="gmaps")
     * @param Request $request
     * @return Response
     */
    public function gmapsAction(Request $request): Response
    {
        $country = $request->get('country', 'lithuania');
        $city = $request->get('city', 'vilnius');
        $street = $request->get('street', 'jasinskio 16');
        $postcode = $request->get('postcode', '01112');

	    $address = new Address($country, $city, $street, $postcode);
	    $this->geocoderService->setGeocoder(new GoogleMapsGeocoderStrategyStrategy());
	    $coordinates = $this->geocoderService->geocode($address);
		if (null === $coordinates) {
			return new JsonResponse([]);
		}
		return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }

    /**
     * @Route(path="/hmaps", name="hmaps")
     * @param Request $request
     * @return Response
     */
    public function hmapsAction(Request $request): Response
    {
        $country = $request->get('country', 'lithuania');
        $city = $request->get('city', 'vilnius');
        $street = $request->get('street', 'jasinskio 16');
        $postcode = $request->get('postcode', '01112');
		$address = new Address($country, $city, $street, $postcode);
		$this->geocoderService->setGeocoder(new HereMapsGeoCoderStrategy());
		$coordinates = $this->geocoderService->geocode($address);
		if (null === $coordinates) {
			return new JsonResponse([]);
		}
		return new JsonResponse(['lat' => $coordinates->getLat(), 'lng' => $coordinates->getLng()]);
    }
}