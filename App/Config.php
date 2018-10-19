<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'project-k';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'red@1night';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    const JWT_INFO = array(
    'jwt' => array(
        'key'       => '',     // Key for signing the JWT's, I suggest generate it with base64_encode(openssl_random_pseudo_bytes(64))
        'algorithm' => 'HS512' // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        ),
    'database' => array(
        'user'     => 'jwt', // Database username
        'password' => 'jwt', // Database password
        'host'     => 'sp-simple-jwt-mysql', // Database host
        'name'     => 'jwt', // Database schema name
    ),
    'serverName' => 'localhost',
    );
}
