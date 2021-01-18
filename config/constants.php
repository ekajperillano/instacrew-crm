<?php

return [
    'date_format' => 'Y-m-d',
    'agreement_date_format' => 'd / m / Y',
    'date_time_format' => 'Y-m-d h:m:s',
    'au_date_time_format' => 'd-m-Y h:m:s',
    'au_date_format' => 'd-m-Y',
    'response_codes' => [
        'error' => '422',
        'success' => '200',
        'database_error' => '500',
        'unauthorized' => '401',
        'forbidden' => '403',
        'not_found' => '422',
    ],
    'pagination_limit' => 50,
    'option_limit' => 10,
    'crypt_key' => 'pM3XMoseqW',
    'upload_options' => [
        'allowed_extensions' => 'gif,jpg,jpeg,png,webp,pdf,docx,xlsx',
        'allowed_mime_type' => 'image/png,image/gif,image/jpeg,image/jpg,image/webp,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'size_limit' => 1024 * 1024 * 100
    ],
    'client_type' => [
        'crew' => 'crew',
        'prospect' => 'prospect',
    ]
];