<?php

return [
    'plugin' => [
        'name' => 'Single Sign On',
        'description' => 'Permet aux utilisateurs frontend de se connecter via le protocole SAML.',
        'permissions' => [
            'manage_saml' => 'Gérer les paramètres SAML'
        ]
    ],

    'components' => [
        'signin_button_label'
    ],

    'settings' => [
        'menu_label' => 'SAML',
        'menu_description' => 'Gérer les paramètres du fournisseur d\'identité.',
        'idp_tab' => 'Fournisseur d\'identité',
        'sp_tab' => 'Fournisseur de service',
        'mapping_tab' => 'Mappage des attributs',
        'options_tab' => 'Options',
        'logs_label' => 'Journal des connexions SSO',
        'logs_description' => 'Affiche la liste des tentatives d\'authentification en Single Sign On.',
        'idp_id_label' => 'Identité de l\'IdP',
        'idp_id_comment' => 'L\'identifiant du fournisseur d\'identité.',
        'idp_acs_label' => 'Adresse ACS',
        'idp_acs_comment' => 'L\'URL Single Sign On du fournisseur d\'identité.',
        'idp_sls_label' => 'Adresse SLS',
        'idp_sls_comment' => 'L\'URL Single Logout du fournisseur d\'identité.',
        'idp_cert_label' => 'Certificat public',
        'idp_cert_comment' => 'Le certificat public du fournisseur d\'identité.',
        'sp_id_label' => 'Identité du SP',
        'sp_id_comment' => 'L\'identifiant du fournisseur de service.',
        'sp_acs_label' => 'Adresse ACS',
        'sp_acs_comment' => 'L\'URL Single Sign On du fournisseur de service.',
        'sp_sls_label' => 'Adresse SLS',
        'sp_sls_comment' => 'L\'URL Single Logout du fournisseur de service.',
        'email_label' => 'Email',
        'email_comment' => 'Nom d\'assertion pour l\'email.',
        'firstname_label' => 'Prénom',
        'firstname_comment' => 'Nom d\'assertion pour le prénom.',
        'lastname_label' => 'Nom',
        'lastname_comment' => 'Nom d\'assertion pour le nom.',
        'debug_label' => 'Mode débogage',
        'debug_comment' => 'Active / désactive le mode débogage.',
        'selfregister_label' => 'Inscription automatique',
        'selfregister_comment' => 'Active / désactive l\'inscription automatique des utilisateurs SSO.',
        'logs_hint' => 'Ce journal affiche la liste des accès au site par les utilisateurs frontend qui utilisent le Single Sign On.',
        'logs_ip' => 'Adresse IP',
        'logs_email' => 'Email',
        'logs_result' => 'Résultat',
        'logs_date' => 'Date et heure'
    ]
];