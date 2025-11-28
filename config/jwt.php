<?php

return [

  /*
  |--------------------------------------------------------------------------
  | JWT Authentication Secret
  |--------------------------------------------------------------------------
  |
  | Don't forget to set this in your .env file, as it will be used to sign
  | your tokens. A helper command is provided for this:
  | `php artisan jwt:secret`
  |
  */
  'secret' => env('JWT_SECRET'),

  /*
  |--------------------------------------------------------------------------
  | JWT Authentication Keys
  |--------------------------------------------------------------------------
  */
  'keys' => [
    'public' => env('JWT_PUBLIC_KEY'),
    'private' => env('JWT_PRIVATE_KEY'),
    'passphrase' => env('JWT_PASSPHRASE'),
  ],

  /*
  |--------------------------------------------------------------------------
  | JWT time to live
  |--------------------------------------------------------------------------
  |
  | Specify the length of time (in minutes) that the token will be valid for.
  | Defaults to 1 hour.
  |
  */
  'ttl' => (int) env('JWT_TTL', 60),

  /*
  |--------------------------------------------------------------------------
  | Refresh time to live
  |--------------------------------------------------------------------------
  |
  | Specify the length of time (in minutes) that the token can be refreshed
  | within. I.E. The user can refresh their token within a 2 week window of
  | the original token being created until they must re-authenticate.
  | Defaults to 2 weeks.
  |
  */
  'refresh_ttl' => (int) env('JWT_REFRESH_TTL', 20160),

  /*
  |--------------------------------------------------------------------------
  | JWT hashing algorithm
  |--------------------------------------------------------------------------
  */
  'algo' => env('JWT_ALGO', Tymon\JWTAuth\Providers\JWT\Provider::ALGO_HS256),

  /*
  |--------------------------------------------------------------------------
  | Required Claims
  |--------------------------------------------------------------------------
  */
  'required_claims' => [
    'iss',
    'iat',
    'exp',
    'nbf',
    'sub',
    'jti',
  ],

  /*
  |--------------------------------------------------------------------------
  | Persistent Claims
  |--------------------------------------------------------------------------
  */
  'persistent_claims' => [
    // 'foo',
    // 'bar',
  ],

  /*
  |--------------------------------------------------------------------------
  | Lock Subject
  |--------------------------------------------------------------------------
  */
  'lock_subject' => true,

  /*
  |--------------------------------------------------------------------------
  | Leeway
  |--------------------------------------------------------------------------
  */
  'leeway' => (int) env('JWT_LEEWAY', 0),

  /*
  |--------------------------------------------------------------------------
  | Blacklist Enabled
  |--------------------------------------------------------------------------
  */
  'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

  /*
  |--------------------------------------------------------------------------
  | Blacklist Grace Period
  |--------------------------------------------------------------------------
  */
  'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

  /*
  |--------------------------------------------------------------------------
  | Cookies encryption
  |--------------------------------------------------------------------------
  */
  'decrypt_cookies' => false,

  /*
  |--------------------------------------------------------------------------
  | Providers
  |--------------------------------------------------------------------------
  */
  'providers' => [
    'jwt' => Tymon\JWTAuth\Providers\JWT\Lcobucci::class,
    'auth' => Tymon\JWTAuth\Providers\Auth\Illuminate::class,
    'storage' => Tymon\JWTAuth\Providers\Storage\Illuminate::class,
  ],

];



