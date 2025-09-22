<?php

// config/company.php

return [
    'name'    => env('APP_OWNER_NAME', 'Nome Azienda'),
    'city' => env('APP_OWNER_CITY','Città'),
    'province' => env('APP_OWNER_PROVINCE','Provincia'),
    'zip_code' => env('APP_OWNER_ZIP_CODE','12345'),
    'address' => env('APP_OWNER_ADDRESS', 'Indirizzo Azienda'),
    'vat'     => env('APP_OWNER_VAT', 'P.IVA'),
    'email'   => env('APP_OWNER_EMAIL', 'email@azienda.it'),
    'site'   => env('APP_OWNER_SITE', 'sitoazienda.it'),
    'number' => env('APP_OWNER_NUMBER','123456789'),
    'job' => env('APP_OWNER_JOB','Professione')
];