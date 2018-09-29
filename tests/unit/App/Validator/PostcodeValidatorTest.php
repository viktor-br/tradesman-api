<?php
declare(strict_types=1);

namespace App\Tests\App\Validator;

use App\Validator\PostcodeValidator;

class PostcodeValidatorTest extends \Codeception\Test\Unit
{
    public function testGermanPostcodesSuccess()
    {
        $postcodeValidator = new PostcodeValidator();
        $this->assertNull($postcodeValidator->validate('12345'), 'Expect 5 digits');

        $this->assertEquals(
            'German postcode should be 5 digits, given abcde',
            $postcodeValidator->validate('abcde'),
            'Expect 5 digits, but got 5 letters'
        );
    }

    /**
     * @dataProvider failureExamples
     * @param string $postcode
     * @param string $expectedMessage
     */
    public function testGermanPostcodesFailure(string $postcode, string $expectedMessage): void
    {
        $postcodeValidator = new PostcodeValidator();

        $this->assertEquals($expectedMessage, $postcodeValidator->validate($postcode));
    }

    /**
     * @return array
     */
    public function failureExamples(): array
    {
        return [
            ['abcde', 'German postcode should be 5 digits, given abcde'],
            ['123456', 'German postcode should be 5 digits, given 123456'],
        ];
    }
}