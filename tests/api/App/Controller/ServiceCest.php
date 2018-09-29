<?php
declare(strict_types=1);

namespace App\Tests\App\Controller;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ServiceCest
{
    /**
     * @param ApiTester $I
     */
    public function servicesHasValueTest(ApiTester $I): void
    {
        $I->sendGET('/services');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'id' => 108140,
                'name' => 'Kellersanierung'
            ]
        ]);
    }
}
