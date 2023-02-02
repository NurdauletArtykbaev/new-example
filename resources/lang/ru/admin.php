<?php

return [
    'common' => [
        'created_at'    => 'Дата создания',
        'updated_at'    => 'Дата обновления'
    ],
    'user' => [
        'singular'  => 'Пользователь',
        'plural'    => 'Пользователи',
        'fields'    => [
            'email'             => 'Почта',
            'personal_number'   => 'Персональный номер работника',
            'first_name'        => 'Имя',
            'last_name'         => 'Фамилия',
            'password'          => 'Пароль',
            'is_online'         => 'На смене',
        ],
    ],
    'store' => [
        'singular'  => 'Склад',
        'plural'    => 'Склады',
        'fields'    => [
            'number'    => 'Номер склада',
            'market_id' => 'Тип склада',
            'name'      => 'Наименование склада',
            'address'   => 'Адрес',
        ]
    ],

    'shift' => [
        'singular'  => 'Смена',
        'plural'    => 'Смены',
        'fields'    => [
            'name'      => 'Наименование',
            'days_work' => 'Кол-во рабочих дней',
            'days_rest' => 'Кол-во дней отдыха',
            'hours'     => 'Кол-во часов на смене',
            'start_at'  => 'Время начала смены'
        ]
    ],
    'notification' => [
        'singular'  => 'Уведомления',
        'plural'    => 'Уведомления',
        'fields'    => [
            'subject'              => 'Заголовка',
            'description'          => 'Описание',
            'key'                  => 'Ключ',
            'text'                 => 'Текст',
            'send_sms'             => 'Отправлять смс',
        ],
    ],
    'date' => [
        'singular'  => 'Дата работы',
        'plural'    => 'Даты работы',
        'fields'    => [
            'data'              => 'Дата',
            'day_of_week'       => 'День недели',
            'day_number_in_week'=> 'Номер дня в неделе',
            'is_holiday'        => 'Праздничный день',
        ]
    ],
];
