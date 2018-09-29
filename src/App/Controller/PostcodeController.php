<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Exception\ValidationException;
use App\Repository\CityRepository;
use App\Validator\PostcodeValidator;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PostcodeController extends FOSRestController
{
    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @var PostcodeValidator
     */
    protected $postcodeValidator;

    public function __construct(CityRepository $cityRepository, PostcodeValidator $postcodeValidator)
    {
        $this->cityRepository = $cityRepository;
        $this->postcodeValidator = $postcodeValidator;
    }

    /**
     * @Rest\Get("/postcode/{postcode}")
     * @param string $postcode
     * @return View
     * @throws ValidationException
     */
    public function getPostcode(string $postcode): View
    {
        $error = $this->postcodeValidator->validate($postcode);

        if ($error !== null) {
            throw new ValidationException($error);
        }

        $cities = $this->cityRepository->findByPostcode($postcode);

        return View::create(
            [
                'items' => array_map(
                    function (City $city) {
                        return $city->getName();
                    },
                    $cities
                )
            ],
            Response::HTTP_OK
        );
    }
}