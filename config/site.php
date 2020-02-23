<?php

return [

    'debug'          => [
        'rules' => true,
    ],

    'currency'       => "zł",

    'deliver_cost'   => [
        'locker'  => 8.99,
        'courier' => 15.99,
    ],

    'deliver_name'   => [
        'INPOST_LOCKER' => "InPost - Paczkomat",
        'COURIER'       => "Kurier",
    ],

    'district_name'  => [
        '1'  => "Dolnośląskie",
        '2'  => "Kujawsko-Pomorskie",
        '3'  => "Lubelskie",
        '4'  => "Lubuskie",
        '5'  => "Łódzkie",
        '6'  => "Małopolskie",
        '7'  => "Mazowieckie",
        '8'  => "Opolskie",
        '9'  => "Podkarpackie",
        '10' => "Podlaskie",
        '11' => "Pomorskie",
        '12' => "Śląskie",
        '13' => "Świętokrzyskie",
        '14' => "Warmińsko-Mazurskie",
        '15' => "Wielkopolskie",
        '16' => "Zachodniopomorskie",
    ],

    'payment_name'   => [
        'PAYU'        => "PayU",
        'PAYPAL'      => "PayPal",
        'PAYMENTCARD' => "Karta kredytowa/debetowa",
    ],

    'product_status' => [
        'INVISIBLE'    => "Niewidoczny",
        'IN_STOCK'     => "Dostępny",
        'INACCESSIBLE' => "Niedostępny",
        'INACTIVE'     => "Nieaktywny",
    ],

    'order_status'   => [
        'CREATED'    => "Stworzone",
        'UNPAID'     => "Niezapłacone",
        'PROCESSING' => "Przetwarzanie płatności",
        'PAID'       => "Zapłacone",
        'REALIZE'    => "Realizowane",
        'SENT'       => "Wysłane",
        'RECEIVE'    => "Odebrane",
        'CANCELED'   => "Anulowana",
    ],

    'user_history'   => [
        'ALL'       => "Wszystko",
        'AC_CHANGE' => "Zmiany na koncie",
        'AUTH'      => "Autoryzacja",
        'BAN'       => "Blokada",
    ],

    'warehouse_item_status'   => [
        'AVAILABLE'       => "Dostępne",
        'UNAVAILABLE' => "Niedostępne",
        'RESERVED'      => "Zarezerwowane",
        'SENT'       => "Wysłane",
        'INVISIBLE'       => "Niewidoczne",
    ],

];
