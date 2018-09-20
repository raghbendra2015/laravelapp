<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Application Name
      |--------------------------------------------------------------------------
      |
      | This value is the name of your application. This value is used when the
      | framework needs to place the application's name in a notification or
      | any other location as required by the application or its packages.
     */
    'api_response' => [
        'OK' => 200,
        'CREATED' => 201,
        'NO_CONTENT' => 204,
        'NOT_MODIFIED' => 304,
        'BAD_REQUEST' => 400,
        'UNAUTHORIZED' => 401,
        'FORBIDDEN' => 403,
        'NOT_FOUND' => 404,
        'METHOD_NOT_ALLOWED' => 405,
        'GONE' => 410,
        'UNSUPPORTED_MEDIA_TYPE' => 415,
        'UNPROCESSABLE_ENTITY' => 422,
        'TO_MANY_REQUESTS' => 429,
        'INTERNAL_SERVER_ERROR' => 500,
        'SERVICE_UNAVAILABLE' => 503,
        'VALIDATION' => 100,
    ],
    'message' => [
        'NO_RECORD_FOUND' => 'No record found.',
    ],
    'common' => [

    ],
    'db_table' => [
        'USERS' => 'users',
        'FILMS' => 'films',
        'COMMENTS' => 'comments',
    ],
    'api_access_token' => [

    ],
    'roles' => [
        'ADMIN' => 1,
        'USER' => 2
    ],
    'DEFAULT_ERROR' => 'Something went wrong.',
    'NO_RECORD_FOUND' => 'No record found.',
];
