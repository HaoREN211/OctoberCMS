<?php

return [
    'plugin' => [
        'name' => 'Bime Analytics',
        'description' => 'Intégration de l\'API BIME.',
        'permissions' => [
            'access_accounts' => 'Gérer les environnements',
            'access_groups' => 'Voir les groupes',
            'access_dashboards' => 'Voir les tableaux de bord',
            'access_viewers' => 'Gérer les lecteurs',
            'access_parameters' => 'Gérer les paramètres de filtre',
            'access_filters' => 'Gérer les filtres'
        ],
        'menus' => [
            'accounts' => 'Environnements',
            'groups' => 'Groupes',
            'dashboards' => 'Tableaux de bord',
            'viewers' => 'Lecteurs',
            'filters' => 'Filtres'
        ]
    ],

    'backend' => [
        'backend_users' => [
            'relation_tab' => 'Bime',
            'bime_groups_section_label' => 'Groupes Bime',
            'bime_groups_section_comment' => 'Spécifier les groupes Bime accessibles pour cet administrateur.'
        ],
        'users' => [
            'relation_tab' => 'Bime',
            'viewer_label' => 'Lecteur',
            'viewer_comments' => 'Lecteur Bime associé à l\'utilisateur.',
            'group_label' => 'Groupe',
            'group_comments' => 'Groupe Bime associé au lecteur.',
            'nogroup' => 'Aucun groupe.'
        ],
        'collections' => [
            'tab' => 'BIME Analytics',
            'accounts_label' => '1. Environnements',
            'accounts_comment' => 'Choisissez les environnements à afficher.',
            'groups_label' => '2. Groupes',
            'groups_comment' => 'Les tableaux de bord autorisées pour les groupes sélectionnés seront accessibles aux administrateurs de cette collection.',
            'bime_section_label' => 'Tableaux de bord autorisés',
        ],
        'accounts' => [
            'record_name' => 'Environnements',
            'error' => 'Impossible d\'accéder à cet environnement !',
            'sync_button' => 'Synchroniser',
            'name_column' => 'Nom',
            'url_column' => 'URL',
            'date_column' => 'Dernière synchronisation',
            'name_label' => 'Nom',
            'name_comment' => 'Nom de l\'environnement BIME.',
            'url_label' => 'URL de l\'API',
            'url_comment' => 'Adresse web liée à l\'environnement (ex: https://exemple.bime.io)',
            'token_label' => 'Token de l\'API',
            'token_comment' => 'Token permettant d\'accéder à l\'API de l\'environnement.'
        ],
        'dashboards' => [
            'record_name' => 'Tableaux de bord',
            'description' => 'Visualisez les tableaux de bord Bime Analytics en vous aidant des filtres ci-dessous.',
            'name_column' => 'Tableau de bord',
            'account_column' => 'Environnement',
            'folder_column' => 'Dossier',
            'date_column' => 'Dernière modification',
            'group_column' => 'Groupe',
            'parameters_tab' => 'Paramètres',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du tableau de bord.',
            'folder_label' => 'Dossier',
            'folder_comment' => 'Dossier du tableau de bord',
            'section_label' => 'Paramètres de filtre',
            'section_comment' => 'Définissez quels sont les paramètres de ce tableau de bord.'
        ],
        'groups' => [
            'record_name' => 'Groupes',
            'description' => 'Visualisez les groupes Bime Analytics en vous aidant des filtres ci-dessous.',
            'name_column' => 'Nom',
            'account_column' => 'Environnement',
            'viewers_column' => 'Nombre d\'utilisateurs',
            'date_column' => 'Dernière modification'
        ],
        'viewers' => [
            'record_name' => 'Lecteurs',
            'description' => 'Visualisez les lecteurs Bime Analytics en vous aidant des filtres ci-dessous et modifiez leur groupe.',
            'login_column' => 'Login',
            'group_column' => 'Groupe',
            'account_column' => 'Environnement',
            'date_column' => 'Dernière modification',
            'login_label' => 'Login',
            'login_comment' => 'Login du lecteur.',
            'group_label' => 'Groupe',
            'group_comment' => 'Groupe du lecteur.',
            'activated_label' => 'Lecteur actif',
            'activated_comment' => 'Indique si le lecteur est bien associé à un utilisateur du portail.',
            'activated_filter' => 'Actif'
        ],
        'parameters' => [
            'record_name' => 'Paramètres',
            'list_return' => 'Retourner aux filtres',
            'name_column' => 'Nom du paramètre',
            'type_column' => 'Type du paramètre',
            'section_label' => 'Paramètre général',
            'section_comment' => 'Ce paramètre sera ré-utilisable sur des tableaux de bord grâce aux filtres.',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du paramètre.',
            'type_label' => 'Type de donnée',
            'type_comment' => 'Choisissez le type de donnée accepté par ce paramètre.',
            'string_option' => 'Texte',
            'date_option' => 'Date',
            'integer_option' => 'Entier',
            'pivot_label' => 'Paramètre URL',
            'pivot_comment' => 'Nom du paramètre dans l\'adresse URL.'
        ],
        'filters' => [
            'record_name' => 'Filtres',
            'collection_column' => 'Collection',
            'parameter_column' => 'Paramètre',
            'page_column' => 'Page',
            'group_column' => 'Groupe',
            'viewer_column' => 'Lecteur',
            'collection_label' => 'Collection',
            'collection_comment' => 'Collection sur laquelle appliquer le filtre.',
            'viewer_switch_label' => 'Filtrer sur le lecteur',
            'parameter_label' => 'Paramètre',
            'parameter_comment' => 'Paramètre du filtre.',
            'value_label' => 'Valeur du filtre',
            'value_comment' => 'Valeur sur laquelle filtrer les tableaux de bord.',
            'page_label' => 'Page',
            'page_comment' => 'Page sur laquelle appliquer le filtre.',
            'all_pages' => 'Toutes les pages',
            'group_label' => 'Groupe',
            'group_comment' => 'Groupe sur lequel appliquer le filtre.',
            'all_groups' => 'Tous les groupes',
            'viewer_label' => 'Lecteur',
            'viewer_comment' => 'Lecteur sur lequel appliquer le filtre.',
            'all_viewers' => 'Tous les lecteurs',
            'step1_section_label' => 'Choix du paramètre',
            'step1_section_comment' => 'Choisissez le paramètre général à filtrer et sur quelle collection l\'appliquer.',
            'step2_section_label' => 'Valeur du paramètre',
            'step2_section_comment' => 'Définissez la valeur du paramètre qui sera appliqué sur les tableaux de bord.',
            'step3_section_label' => 'Réduire la portée du filtre',
            'step3_section_comment' => 'Vous pouvez affiner la portée du filtre en choisissant une page et/ou un groupe d\'utilisateurs.',
        ],
        'messages' => [
            'sync_success' => 'Synchronisation réussie !',
            'sync_error' => 'Synchronisation échouée !'
        ]
    ]
];

?>
