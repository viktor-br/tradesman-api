<?php
declare(strict_types=1);

namespace App\Tests\App\Validator;

use App\Entity\City;
use App\Entity\Service;
use App\Repository\CityRepository;
use App\Repository\ServiceRepository;
use App\Request\JobRequest;
use App\Validator\JobRequestValidator;
use App\Validator\PostcodeValidator;
use Codeception\Test\Unit;

class JobRequestValidatorTest extends Unit
{
    /**
     * @param JobRequest $jobRequest
     * @param City|null $cityEntity
     * @param Service|null $serviceEntity
     * @param array $expectedErrors
     * @dataProvider dataExamples
     */
    public function testValidatorSuccess(JobRequest $jobRequest, ?City $cityEntity, ?Service $serviceEntity, array $expectedErrors): void
    {
        $serviceRepositoryMock = $this->createMock(ServiceRepository::class);
        $cityRepositoryMock = $this->createMock(CityRepository::class);
        $postcodeValidator = new PostcodeValidator();

        $jobRequestValidator = new JobRequestValidator($serviceRepositoryMock, $cityRepositoryMock, $postcodeValidator);

        $serviceRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($jobRequest->getServiceId())
            ->willReturn($serviceEntity);

        $cityRepositoryMock
            ->expects($this->once())
            ->method('findOneByPostcodeAndCity')
            ->with($jobRequest->getPostcode(), $jobRequest->getCity())
            ->willReturn($cityEntity);

        $actualErrors = $jobRequestValidator->validate($jobRequest);

        $this->assertEquals($expectedErrors, $actualErrors);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function dataExamples(): array
    {
        $title = 'Job title';
        $fulfillmentDate = (new \DateTime())->add(new \DateInterval('P1D'));
        $description = 'desc';
        $postcode = '12345';
        $city = 'Berlin';
        $serviceId = 111;

        $jobRequestSuccess = new JobRequest();
        $jobRequestSuccess->setTitle($title);
        $jobRequestSuccess->setFulfillmentDate($fulfillmentDate);
        $jobRequestSuccess->setDescription($description);
        $jobRequestSuccess->setPostcode($postcode);
        $jobRequestSuccess->setCity($city);
        $jobRequestSuccess->setServiceId($serviceId);

        $jobRequestWithWrongTitle = clone $jobRequestSuccess;
        $jobRequestWithWrongTitle->setTitle('abcd');

        $jobRequestWithWrongFulfillmentDate = clone $jobRequestSuccess;
        $jobRequestWithWrongFulfillmentDate->setFulfillmentDate((new \DateTime())->sub(new \DateInterval('P1D')));

        $cityEntity = new City();
        $cityEntity->setName($city);
        $cityEntity->setPostcode($postcode);

        $serviceEntity = new Service();
        $serviceEntity->setId($serviceId);

        return [
            [
                $jobRequestSuccess,
                $cityEntity,
                $serviceEntity,
                []
            ],
            [
                $jobRequestSuccess,
                null,
                $serviceEntity,
                [
                    sprintf(
                        JobRequestValidator::ERROR_MSG_POSTCODE_AND_CITY_MATCHING,
                        $jobRequestSuccess->getPostcode(),
                        $jobRequestSuccess->getCity()
                    )
                ]
            ],
            [
                $jobRequestSuccess,
                $cityEntity,
                null,
                [sprintf(JobRequestValidator::ERROR_MSG_SERVICE_NOT_FOUND, $jobRequestSuccess->getServiceId())]
            ],
            [
                $jobRequestWithWrongTitle,
                $cityEntity,
                $serviceEntity,
                [
                    sprintf(
                        JobRequestValidator::ERROR_MSG_TITLE_LENGTH,
                    JobRequestValidator::TITLE_LENGTH_MIN,
                    JobRequestValidator::TITLE_LENGTH_MAX
                    )
                ]
            ],
            [
                $jobRequestWithWrongFulfillmentDate,
                $cityEntity,
                $serviceEntity,
                [JobRequestValidator::ERROR_MSG_FULFILLMENT_DATE]
            ],
        ];
    }
}