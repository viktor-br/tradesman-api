<?php
declare(strict_types=1);

namespace App\Tests\api\App\Controller;

use App\Tests\ApiTester;
use App\Validator\JobRequestValidator;
use Codeception\Util\HttpCode;

class JobCest
{
    /**
     * // TODO split test
     * @param ApiTester $I
     * @throws \Exception
     */
    public function createJobAndCheckTest(ApiTester $I): void
    {
        // Create new Job via PUT request
        $fulfillmentDate = (new \DateTime('midnight'))->add(new \DateInterval('P12D'));
        $jobData = [
            "title" => "Job in Berlin",
            "postcode" => '10115',
            "city" => "Berlin",
            "description" => "full description of the job in Berlin",
            "fulfillment_date" => $fulfillmentDate->format('Y-m-d'),
            "service_id" => 804040 // Sonstige Umzugsleistungen
        ];
        $I->haveHttpHeader('Content-Type','application/json');
        $I->sendPUT('/job', $jobData);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//id');
        $jobResponseData = json_decode($I->grabResponse(), true);

        // Get additional field values
        $jobId = $jobResponseData['id'];
        $fulfillmentDate = $jobResponseData['fulfillment_date'];
        $createdAt = $jobResponseData['created_at'];
        $updatedAt = $jobResponseData['updated_at'];

        $expectedJobData = [
            "id" => $jobId,
            "title" => "Job in Berlin",
            "postcode" => '10115',
            "city" => "Berlin",
            "description" => "full description of the job in Berlin",
            "fulfillment_date" => $fulfillmentDate,
            "service" => [
                'id' => 804040,
                'name' => 'Sonstige Umzugsleistungen'
            ],
            "is_canceled" => false,
            "created_at" => $createdAt,
            "updated_at" => $updatedAt
        ];

        // Send GET request to compare with initial data
        $I->sendGET('/job/' . $jobId);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedJobData);

        // Send POST request to update the job
        $jobData['title'] = 'Modified job title';
        $expectedJobData['title'] = 'Modified job title';
        $I->sendPOST('/job/' . $jobId, $jobData);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        // Get field values, which changed (updated_at)
        $jobResponseData = json_decode($I->grabResponse(), true);
        $expectedJobData['updated_at'] = $jobResponseData['updated_at'];

        $I->seeResponseContainsJson($expectedJobData);

        // Get field values, which changed (updated_at)
        $jobResponseData = json_decode($I->grabResponse(), true);
        $expectedJobData['updated_at'] = $jobResponseData['updated_at'];

        // Send GET request to compare with initial data
        $I->sendGET('/job/' . $jobId);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($expectedJobData);

        // Cancel job
        $I->sendDELETE('/job/' . $jobId);
        $I->seeResponseCodeIs(HttpCode::OK);

        $expectedJobData['is_canceled'] = true;

        $I->seeResponseContainsJson($expectedJobData);
    }

    /**
     * @param ApiTester $I
     * @throws \Exception
     */
    public function jobsSearchTest(ApiTester $I): void
    {
        $searchData = [
            'postcode' => ['10115'],
            'city' => ['Berlin'],
            'service_id' => [804040],
            'limit' => 5
        ];

        $I->haveHttpHeader('Content-Type','application/json');
        $I->sendPOST('/search/job', $searchData);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson([
            [
                'postcode' => '10115',
                'city' => 'Berlin',
            ]
        ]);
    }

    /**
     * @param ApiTester $I
     * @throws \Exception
     */
    public function failedCreateJobTest(ApiTester $I): void
    {
        // Create new Job via PUT request
        $fulfillmentDate = (new \DateTime('midnight'))->add(new \DateInterval('P12D'));
        $jobData = [
            "title" => "abc",
            "postcode" => '10115',
            "city" => "Berlin",
            "description" => "full description of the job in Berlin",
            "fulfillment_date" => $fulfillmentDate->format('Y-m-d'),
            "service_id" => 804040 // Sonstige Umzugsleistungen
        ];
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/job', $jobData);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => 400,
            'message' => sprintf(
                JobRequestValidator::ERROR_MSG_TITLE_LENGTH,
                JobRequestValidator::TITLE_LENGTH_MIN,
                JobRequestValidator::TITLE_LENGTH_MAX
            )
        ]);
    }
}