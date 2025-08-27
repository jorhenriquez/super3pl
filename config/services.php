<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'alerce' => [
        'base_url' => env('ALERCE_STOCK_URL'),
        'token_name' => env('ALERCE_STOCK_TOKEN_NAME'),
        'token_stock' => env('ALERCE_STOCK_TOKEN'),
        'token_estadopedidos' => env('ALERCE_ESTADO_PEDIDO_TOKEN'),
        'delegacion' => env('ALERCE_STOCK_DELEGACION'),
        'codigo_cliente' => env('ALERCE_CODIGO_CLIENTE'),
        'token_actas_estandar' => env('ALERCE_ESTADO_ACTAS_ESTANDAR_TOKEN'),
        'token_actas_ubicadas' => env('ALERCE_ESTADO_ACTAS_UBICADAS_TOKEN'),
    ],


];
