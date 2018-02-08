<?php
return [
    'plugin' => [
        'name' => 'Tracker',
        'description' => 'Support du tracking Google Analytics par portail.',
    ],

    'components' => [
        'ga' => 'Tracker Google',
        'ga_description' => 'Permet de suivre le trafic de la page.'
    ],

    'backend' => [
        'portals' => [
            'gaid_label' => 'ID Google Analytics',
            'gaid_comment' => 'Exemple : UA-80775012-1'
        ]
    ]
];
