<?php

return [
    'disk' => env('QRCODE_DISK', 'public'),
    'directory' => env('QRCODE_DIRECTORY', 'qr-codes'),
    'format' => env('QRCODE_FORMAT', 'svg'),
    'size' => env('QRCODE_SIZE', 300),
    'error_correction' => env('QRCODE_ERROR_CORRECTION', 'H'),
    'margin' => env('QRCODE_MARGIN', 2),
];
