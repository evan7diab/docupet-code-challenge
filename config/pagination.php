<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination Settings
    |--------------------------------------------------------------------------
    |
    | These values control the default pagination behavior for API endpoints.
    | You can override these values in your .env file.
    |
    */

    'default_per_page' => (int) env('PAGINATION_DEFAULT_PER_PAGE', 15),

    'min_per_page' => (int) env('PAGINATION_MIN_PER_PAGE', 1),

    'max_per_page' => (int) env('PAGINATION_MAX_PER_PAGE', 100),

];
