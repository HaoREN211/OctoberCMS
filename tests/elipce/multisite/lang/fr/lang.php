<?php
return [
    'plugin' => [
        'name' => 'Portails',
        'description' => 'Support du multisite par portail.',
        'permissions' => [
            'access_portals' => 'Gérer les portails'
        ],
        'menus' => [
            'portals' => 'Portails'
        ]
    ],

    'components' => [
        'portal' => 'Portail',
        'portal_description' => 'Charge les données du portail dans la page.',
        'redirector' => 'Redirecteur',
        'redirector_description' => 'Redirige l\'utilisateur connecté vers son portail.'
    ],

    'backend' => [
        'backend_users' => [
            'relation_label' => 'Portail',
            'relation_comment' => 'Portail auquel l\'administrateur est rattaché.',
            'relation_tab' => 'Portail'
        ],
        'users' => [
            'relation_label' => 'Portail',
            'relation_comment' => 'Portail auquel l\'utilisateur est rattaché.',
        ],
        'portals' => [
            'record_name' => 'Portails',
            'backend_tab' => 'Paramètres',
            'name_column' => 'Nom',
            'theme_column' => 'Thème',
            'date_column' => 'Dernière modification',
            'clear_cache_button' => 'Vider le cache',
            'cache_cleared' => 'Cache vidé !',
            'db_error' => 'Multisite plugin introuvable, réinstallez le plugin.',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du portail.',
            'description_label' => 'Description',
            'description_comment' => 'Courte description du portail.',
            'navbarlogo_label' => 'Logo du menu',
            'navbarlogo_comment' => 'Logo du menu de navigation du portail.',
            'loginlogo_label' => 'Logo de connexion',
            'loginlogo_comment' => 'Logo de la page de connexion du portail.',
            'favicon_label' => 'Favicon',
            'favicon_comment' => 'Icône du portail dans le navigateur.',
            'domain_label' => 'Domaine',
            'domain_comment' => 'Domaine associé au portail (ex: societe.com).',
            'subdomain_label' => 'Sous-domaine',
            'subdomain_comment' => 'Sous-domaine associé au portail.',
            'theme_label' => 'Thème',
            'theme_comment' => 'Thème appliqué pour ce portail.',
            'less_label' => 'Feuille de styles LESS',
            'less_comment' => 'Principaux éléments du thème.',
            'less_tab' => 'Style',
            'less_error' => 'Une erreur s\'est produite pendant la compilation des styles !'
        ]
    ]
];
