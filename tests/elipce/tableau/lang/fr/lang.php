<?php

return [
    'plugin' => [
        'name' => 'Tableau Online',
        'description' => 'Intégration de l\'API Tableau Software.',
        'permissions' => [
            'access_sites' => 'Gérer les sites',
            'access_groups' => 'Voir les groupes',
            'access_workbooks' => 'Voir les tableaux de bord',
            'access_viewers' => 'Gérer les utilisateurs'
        ],
        'menus' => [
            'sites' => 'Sites',
            'groups' => 'Groupes',
            'workbooks' => 'Tableaux de bord',
            'viewers' => 'Utilisateurs'
        ]
    ],

    'backend' => [
        'sites' => [
            'record_name' => 'Sites',
            'sync_button' => 'Synchroniser',
            'name_column' => 'Nom',
            'url_column' => 'URL',
            'date_column' => 'Dernière synchronisation',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du site Tableau Online.',
            'url_label' => 'URI',
            'url_comment' => 'URI du site auquel se connecter (ex: elipcebi)',
            'login_label' => 'Login',
            'login_comment' => 'Login de l\'administrateur du site.',
            'password_label' => 'Mot de passe',
            'password_comment' => 'Mot de passe de l\'administrateur du site.'
        ],
        'workbooks' => [
            'record_name' => 'Tableau de bords',
            'description' => 'Visualisez les tableaux de bord Tableau Online en vous aidant des filtres ci-dessous.',
            'name_column' => 'Tableau de bord',
            'project_column' => 'Projet',
            'site_column' => 'Site',
            'views_column' => 'Nombre de vues',
            'groups_column' => 'Groupes',
            'date_column' => 'Dernière modification'
        ],
        'groups' => [
            'record_name' => 'Groupes',
            'description' => 'Visualisez les groupes Tableau Online en vous aidant des filtres ci-dessous.',
            'name_column' => 'Nom',
            'site_column' => 'Site',
            'viewers_column' => 'Nombre d\'utilisateurs',
            'date_column' => 'Dernière modification'
        ],
        'messages' => [
            'sync_success' => 'Synchronisation réussie !',
            'sync_error' => 'Synchronisation échouée !'
        ]
    ]
];