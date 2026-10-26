<?php

return [
    'public_key' => env('VAPID_PUBLIC_KEY'),
    'private_key' => env('VAPID_PRIVATE_KEY'),
    'subject' => env('VAPID_SUBJECT', 'mailto:admin@ndjina.gab'),
    'ttl' => (int) env('VAPID_TTL', 3600),
];
