<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SumLessThan implements ValidationRule
{

    protected $maxValue;

    public function __construct($maxValue)
    {
        $this->maxValue = $maxValue;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get the request data
        $data = request()->all();

        // Calculate the sum of the specified fields
        $sum = $data['aluminium'] + $data['copper'] + $data['iron'] + $data['dust'];

        // Check if the sum is greater than the specified max value
        if ($sum > $this->maxValue) {
            $fail('The total sum of the fields must not exceed ' . $this->maxValue);
        }
    }
}
