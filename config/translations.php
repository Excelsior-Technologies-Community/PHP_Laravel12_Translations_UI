<?php

return [
    'default_locale' => 'en',
    'locales' => ['en', 'fr', 'es'], // Supported languages
    'ui_path' => 'translations',     // URL for dashboard
    'middleware' => ['auth'],        // Protect dashboard
];