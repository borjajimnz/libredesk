<?php

return [
    'core_settings' => [
        'name',
        'logo',
        'theme_color',
        'allowed_register_domains',
        'language',
    ],

    'languages' => [
        'en' => 'English',
        'es' => 'Spanish',
    ],

    'version' => '1.0.1',
    'demo' => ENV('LIBREDESK_DEMO', false),
];
