<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used throughout the application for
    | various messages that we need to display to the user.
    |
    */

    // Pet registration
    'pet' => [
        'registered' => 'Pet ":name" has been registered.',
        'registered_success' => 'Pet registered successfully.',
    ],

    // API authentication
    'api' => [
        'key_not_configured' => 'API key not configured.',
        'key_invalid' => 'Invalid or missing API key.',
    ],

    // Pet validation messages
    'validation' => [
        'type_id' => [
            'required' => 'Please select a pet type.',
            'exists' => 'The selected pet type is invalid.',
        ],
        'name' => [
            'required' => 'Please enter your pet\'s name.',
            'string' => 'The pet name must be text.',
            'max' => 'The pet name cannot exceed :max characters.',
        ],
        'gender' => [
            'required' => 'Please select your pet\'s gender.',
            'in' => 'The gender must be either male or female.',
        ],
        'breed_id' => [
            'exists' => 'The selected breed is invalid.',
        ],
        'breed_clarification' => [
            'in' => 'The breed clarification must be either unknown or mix.',
        ],
        'breed_text' => [
            'string' => 'The breed description must be text.',
            'max' => 'The breed description cannot exceed :max characters.',
        ],
        'knows_dob' => [
            'required' => 'Please indicate if you know your pet\'s date of birth.',
            'in' => 'Please select yes or no for date of birth knowledge.',
        ],
        'approx_age_years' => [
            'integer' => 'The approximate age must be a whole number.',
            'min' => 'The approximate age must be at least :min year.',
            'max' => 'The approximate age cannot exceed :max years.',
        ],
        'dob' => [
            'date' => 'Please enter a valid date of birth.',
            'before_or_equal' => 'The date of birth cannot be in the future.',
        ],
    ],
];
