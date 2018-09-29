<?php
declare(strict_types=1);

namespace App\Validator;

use App\Repository\CityRepository;
use App\Repository\ServiceRepository;
use App\Request\JobRequest;

class JobRequestValidator
{
    const TITLE_LENGTH_MIN = 5;
    const TITLE_LENGTH_MAX = 50;

    const ERROR_MSG_SERVICE_NOT_FOUND = 'Service id=%d not found';
    const ERROR_MSG_TITLE_LENGTH = 'Title should have min %d and maximum %d symbols';
    const ERROR_MSG_FULFILLMENT_DATE = 'Fulfillment date could not be in the past';
    const ERROR_MSG_POSTCODE_AND_CITY_MATCHING = 'Postcode %s and city %s not matched';

    /**
     * @var ServiceRepository
     */
    protected $serviceRepository;

    /**
     * @var PostcodeValidator
     */
    protected $postcodeValidator;

    /**
     * @var CityRepository
     */
    protected $cityRepository;

    /**
     * @param ServiceRepository $serviceRepository
     * @param CityRepository $cityRepository
     * @param PostcodeValidator $postcodeValidator
     */
    public function __construct(
        ServiceRepository $serviceRepository,
        CityRepository $cityRepository,
        PostcodeValidator $postcodeValidator
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->postcodeValidator = $postcodeValidator;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param JobRequest $jobRequest
     * @return array
     */
    public function validate(JobRequest $jobRequest): array
    {
        $errors = [];

        $service = $this->serviceRepository->find($jobRequest->getServiceId());
        if ($service === null) {
            $errors[] = sprintf(self::ERROR_MSG_SERVICE_NOT_FOUND, $jobRequest->getServiceId());
        }

        $postcodeError = $this->postcodeValidator->validate($jobRequest->getPostcode());
        if ($postcodeError !== null) {
            $errors[] = $postcodeError;
        }

        $titleLen = mb_strlen($jobRequest->getTitle());
        if ($titleLen < self::TITLE_LENGTH_MIN || $titleLen > self::TITLE_LENGTH_MAX) {
            $errors[] = sprintf(self::ERROR_MSG_TITLE_LENGTH, self::TITLE_LENGTH_MIN, self::TITLE_LENGTH_MAX);
        }

        if ($jobRequest->getFulfillmentDate() < new \DateTime()) {
            $errors[] = self::ERROR_MSG_FULFILLMENT_DATE;
        }

        $city = $this->cityRepository->findOneByPostcodeAndCity($jobRequest->getPostcode(), $jobRequest->getCity());
        if ($city === null) {
            $errors[] = sprintf(self::ERROR_MSG_POSTCODE_AND_CITY_MATCHING, $jobRequest->getPostcode(), $jobRequest->getCity());
        }

        return $errors;
    }
}