<?php
declare(strict_types=1);

namespace App\Tests\App\Controller;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class PostcodeCest
{
    /**
     * @param ApiTester $I
     */
    public function postcodeValidTest(ApiTester $I): void
    {
        $I->sendGET('/postcode/99999');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([]);
    }

    /**
     * @param ApiTester $I
     */
    public function postcodeCitiesTest(ApiTester $I): void
    {
        $I->sendGET('/postcode/10115');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'items' => [
                'Berlin'
            ]
        ]);
    }

    /**
     * @param ApiTester $I
     */
    public function postcodeNotValidTest(ApiTester $I): void
    {
        $I->sendGET('/postcode/abcde');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContainsJson([
            'code' => 404,
            'message' => "German postcode should be 5 digits, given abcde"
        ]);
    }
}
