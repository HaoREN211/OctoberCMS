<?php

return [
    'plugin' => [
        'name' => 'Google Analytics',
        'description' => 'Intégration de l\'API Google Analytics.',
        'permissions' => [
            'access_accounts' => 'Gérer les comptes',
            'access_views' => 'Voir les vues',
            'access_charts' => 'Gérer les graphiques'
        ],
        'menus' => [
            'accounts' => 'Comptes',
            'views' => 'Vues',
            'charts' => 'Graphiques'
        ]
    ],

    'backend' => [
        'accounts' => [
            'record_name' => 'Comptes',
            'sync_button' => 'Synchroniser',
            'name_column' => 'Nom',
            'email_column' => 'Email',
            'date_column' => 'Dernière synchronisation',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du compte.',
            'email_label' => 'Email',
            'email_comment' => 'Email de l\'API.',
            'p12key_label' => 'Clé P12',
            'p12key_comment' => 'Clé d\'identification à l\'API.'
        ],
        'views' => [
            'record_name' => 'Vues',
            'description' => 'Visualisez les vues Google Analytics en vous aidant des filtres ci-dessous.',
            'name_column' => 'Nom de la vue',
            'type_column' => 'Type',
            'date_column' => 'Dernière modification',
            'property_column' => 'Propriété',
            'account_column' => 'Compte',
        ],
        'charts' => [
            'record_name' => 'Graphiques',
            'description' => '',
            'name_column' => 'Nom',
            'property_column' => 'Propriété',
            'view_column' => 'Vue',
            'date_column' => 'Dernière modification',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du graphique.',
            'view_label' => 'Vue',
            'view_comment' => 'La vue du graphique.',
            'property_label' => 'Propriété',
            'property_comment' => 'La propriété de la vue.',
            'type_label' => 'Type de graphique',
            'type_comment' => 'Le type de graphique à afficher.',
            'line_option' => 'Courbe',
            'column_option' => 'Histogramme',
            'bar_option' => 'Diagramme en barres',
            'pie_option' => 'Camembert',
            'table_option' => 'Tableau',
            'geo_option' => 'Carte géographique',
            'start_date_label' => 'Date de début',
            'start_date_comment' => 'La date de début de la période à observer.',
            'end_date_label' => 'Date de fin',
            'end_date_comment' => 'La date de fin de la période observer.',
            'metadata_tab' => 'Champs',
            'period_tab' => 'Période',
            'metric_label' => 'Mesure',
            'metric_comment' => 'La mesure à observer.',
            'dimension_label' => 'Dimension',
            'dimension_comment' => 'La dimension à travers laquelle observer la mesure.'
        ],
        'messages' => [
            'sync_success' => 'Synchronisation réussie !',
            'sync_error' => 'Synchronisation échouée !'
        ]
    ]
];

?>
