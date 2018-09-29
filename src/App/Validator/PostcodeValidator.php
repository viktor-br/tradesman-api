<?php
declare(strict_types=1);

namespace App\Validator;

class PostcodeValidator
{
    /**
     * @param string $postcode
     * @return string|null
     */
    public function validate(string $postcode): ?string
    {
        if (!preg_match("/^[0-9]{5}$/", $postcode)) {
            return sprintf("German postcode should be 5 digits, given %s", $postcode);
        }

        return null;
    }
}