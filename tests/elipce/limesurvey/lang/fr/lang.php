<?php

return [
    'plugin' => [
        'name'        => 'LimeSurvey',
        'description' => 'Gestion d\'enquêtes automatisées sur LimeSurvey.',
        'permissions' => [
            'access_accounts'  => 'Gérer les comptes',
            'access_templates' => 'Gérer les modèles',
            'access_stories'   => 'Gérer les scénarios',
            'access_sessions'  => 'Gérer les sessions'
        ],
        'menus'       => [
            'accounts'     => 'Comptes',
            'templates'    => 'Modèles d\'enquête',
            'stories'      => 'Scénarios',
            'sessions'     => 'Sessions',
            'participants' => 'Participants',
            'surveys'      => 'Enquêtes'
        ]
    ],

    'components' => [],

    'backend' => [
        'accounts'     => [
            'record_name'                    => 'compte',
            'name_column'                    => 'Nom',
            'date_column'                    => 'Dernière modification',
            'authentication_section_label'   => 'Informations d\'authentification',
            'authentication_section_comment' => 'Renseignez les informations nécessaire à l\'authentification du compte LimeSurvey.',
            'name_label'                     => 'nom du compte',
            'name_comment'                   => 'Donnez un nom à ce compte.',
            'password_label'                 => 'mot de passe',
            'password_comment'               => 'Mot de passe du compte.',
            'login_label'                    => 'nom de l\'utilisateur',
            'login_comment'                  => 'Nom de l\'utilisateur du compte.',
            'url_label'                      => 'URL du serveur',
            'url_comment'                    => 'Exemple : http(s)://domaine.com/index.php/admin/remotecontrol',
            'portals_section_label'          => 'Paramètres du compte',
            'portals_section_comment'        => 'Choisissez les portails rattachés à ce compte LimeSurvey.',
            'portals_empty'                  => 'Aucun portail disponible.'
        ],
        'roles'        => [
            'record_name'         => 'rôle',
            'name_column'         => 'Intitulé',
            'description_column'  => 'Description',
            'code_column'         => 'Nom colonne',
            'date_column'         => 'Dernière modification',
            'presurveys_column'   => 'Enquêtes',
            'name_label'          => 'intitulé',
            'name_comment'        => 'Intitulé du rôle. (exemple: Chef de projet)',
            'description_label'   => 'description',
            'description_comment' => 'Description du rôle.',
            'code_label'          => 'code',
            'code_comment'        => 'Code associé au rôle. (exemple: chefprojet)'
        ],
        'sessions'     => [
            'record_name'                  => 'session',
            'name_column'                  => 'Nom',
            'story_column'                 => 'Scénario',
            'participants_column'          => 'Participants',
            'date_column'                  => 'Dernière modification',
            'participants_tab'             => 'Participants',
            'surveys_tab'                  => 'Enquêtes',
            'name_label'                   => 'nom',
            'name_comment'                 => 'Nom de la session.',
            'start_date_label'             => 'date de début',
            'start_date_comment'           => 'Date où débute la session.',
            'start_date_validation_after'  => 'La date de début ne peut commencer avant aujourd\'hui.',
            'start_date_validation_before' => 'La date de début doit être antérieure à la date de fin.',
            'end_date_label'               => 'date de fin',
            'end_date_comment'             => 'Date où se termine la session.',
            'story_label'                  => 'scénario',
            'story_comment'                => 'Scénario de la session.',
            'portal_section_label'         => 'Portail',
            'portal_section_comment'       => 'Portail de la session.',
            'portal_label'                 => 'portail',
            'surveys_label'                => 'enquêtes',
            'participants_label'           => 'participants',
            'upload_label'                 => 'Importer depuis Excel',
            'upload_placeholder'           => 'Déposez votre fichier Excel ici.',
            'dates_section_label'          => 'Déroulement de la session',
            'general_section_label'        => 'Informations générales',
            'surveys_section_label'        => 'Enquêtes de la session',
            'surveys_section_comment'      => 'Définissez les durées accordées aux participants en fonction de leur rôle.',
            'participants_section_label'   => 'Participants de la session',
            'participants_section_comment' => 'Importez des participants depuis un fichier Excel et modifiez-les ultérieurement.',
            'import_section_one_label'     => '1. Envoi du fichier Excel',
            'import_section_one_comment'   => 'Envoyez votre fichier Excel contenant les participants de la session.',
            'import_section_two_label'     => '2. Correspondance des colonnes',
            'import_section_two_comment'   => 'Faites correspondre les colonnes du fichier avec les champs des participants.',
            'custom_columns_section'       => 'Colonnes personnalisées',
            'required_columns_section'     => 'Colonnes obligatoires',
            'mask_columns_section'         => 'Colonnes du masque',
            'columns_prompt'               => 'Ajoutez des attributs personnalisés en cliquant ici.',
            'uid_column_label'             => 'colonne des identifiants',
            'email_column_label'           => 'colonne des emails',
            'fn_column_label'              => 'colonne des prénoms',
            'sn_column_label'              => 'colonne des noms',
            'role_column_label'            => 'colonne des rôles',
            'columns_column'               => 'nom du champ',
            'columns_type'                 => 'type du champ',
            'active_surveys'               => 'Enquêtes en cours',
            'pending_surveys'              => 'Enquêtes à venir',
            'expired_surveys'              => 'Enquêtes terminées',
            'statistics_section_label'     => 'Statistiques',
            'statistics_section_comment'   => 'Aucune statistique disponible...',
            'duplicated_participant'       => 'Identifiant non unique à la ligne ',
            'download_participants_file'   => 'Télécharger le fichier des participants vierge.',
            'empty_file'                   => 'Le fichier des participants est vide.',
            'no_account'                   => 'Vous ne pouvez pas créer de session car aucun compte LimeSurvey n\'a été configuré.',
            'no_file'                      => 'Veuillez uploader le fichier des participants.',
            'invalid_participant'          => 'Participant invalide à la ligne ',
            'invalid_column'               => 'Les attributs personnalisés ne correspondent à aucune colonne.',
            'columns_not_found'            => 'Les colonnes suivantes n\'ont pas été trouvées : ',
            'role_not_found'               => 'Le rôle d\'un des participants importés n\'a pas été reconnu.',
            'story_not_found'              => 'Aucun scénario trouvé.',
            'surveys_creation_error'       => 'Les questionnaires de la session n\'ont pas pu être créés sur LimeSurvey...',
            'api_error'                    => 'Connexion au serveur LimeSurvey impossible, merci de vérifier les identifiants du compte.'
        ],
        'stories'      => [
            'record_name'                => 'scénario',
            'name_column'                => 'Nom',
            'roles_column'               => 'Rôles',
            'presurveys_column'          => 'Enquêtes',
            'date_column'                => 'Dernière modification',
            'presurveys_tab'             => 'Enquêtes',
            'presurveys_label'           => 'enquêtes',
            'roles_tab'                  => 'Rôles',
            'roles_label'                => 'rôles',
            'name_label'                 => 'nom',
            'name_comment'               => 'Nom du scénario.',
            'type_label'                 => 'type',
            'type_comment'               => 'Type de scénario.',
            'collection_label'           => 'collection',
            'collection_comment'         => 'Collection du scénario.',
            'statistics_section_label'   => 'Statistiques',
            'statistics_empty'           => 'Aucune statistique disponible...',
            'roles_section_label'        => 'Rôles du scénario',
            'roles_section_comment'      => 'Créez de nouveaux rôles disponibles pour vos enquêtes.',
            'presurveys_section_label'   => 'Enquêtes du scénario',
            'presurveys_section_comment' => 'Ajoutez de nouvelles enquêtes à partir de la bibliothèque d\'enquêtes et associez-leur des rôles.',
            'general_section_label'      => 'Informations générales',
            'active_sessions'            => 'Sessions actives',
            'pending_sessions'           => 'Session en attente',
            'expired_sessions'           => 'Sessions terminées',
            'mask_tab'                   => 'Masque',
            'no_mask'                    => 'Aucun masque.',
            'mask_section_label'         => 'Masque Excel',
            'mask_section_comment'       => 'Définissez un masque de vérification pour vos attributs personnalisés.'
        ],
        'templates'    => [
            'record_name'               => 'modèle d\'enquête',
            'name_column'               => 'Nom',
            'collection_column'         => 'Collection',
            'date_column'               => 'Dernière modification',
            'name_label'                => 'nom',
            'name_comment'              => 'Nom du modèle d\'enquête.',
            'collection_label'          => 'collection',
            'collection_comment'        => 'Collection du modèle d\'enquête.',
            'structure_label'           => 'fichier LimeSurvey',
            'structure_comment'         => 'Fichier contenant la structure de l\'enquête.',
            'structure_prompt'          => 'Déposer votre fichier .lss par glisser-déposer ou cliquez sur l\'icône à droite.',
            'questions_section_label'   => 'Questions du questionnaire',
            'questions_section_comment' => 'Cliquez sur les questions pour les lier avec d\'autres questionnaires.',
            'questions_tab'             => 'Questions'
        ],
        'presurveys'   => [
            'record_name'              => 'pré-enquête',
            'name_column'              => 'Nom',
            'start_days_column'        => 'T0 + X',
            'end_days_column'          => 'TFin + X',
            'roles_column'             => 'Rôles',
            'planning_tab'             => 'Plannification',
            'roles_tab'                => 'Rôles',
            'name_label'               => 'nom',
            'name_comment'             => 'Nom de l\'enquête.',
            'planning_section_label'   => 'Plannification de l\'enquête',
            'planning_section_comment' => 'Choisissez quand doit démarrer l\'enquête par rapport à la date de début ou de fin de session.',
            'after_start_option'       => 'Après le début de la session',
            'before_start_option'      => 'Avant le début de la session',
            'after_end_option'         => 'Après la fin de la session',
            'before_end_option'        => 'Avant la fin de la session',
            'days_label'               => 'nombre de jours',
            'days_placeholder'         => 'Nombre de jours',
            'duration_label'           => 'durée de validité',
            'duration_comment'         => 'Durée laissée aux participants pour répondre à l\'enquête.',
            'template_label'           => 'modèle',
            'template_comment'         => 'Modèle d\'enquête à utiliser.',
            'roles_label'              => 'rôles associés à cette enquête',
            'roles_comment'            => 'Les participants qui possèdent ces rôles participeront à l\'enquête.',
            'roles_empty'              => 'Aucun rôle disponible.'
        ],
        'surveys'      => [
            'record_name'          => 'enquête',
            'name_column'          => 'Nom',
            'start_date_column'    => 'Date de début',
            'end_date_column'      => 'Date de fin',
            'role_column'          => 'Rôle',
            'participants_column'  => 'Participants',
            'planning_tab'         => 'Plannification',
            'tracking_tab'         => 'Suivi des réponses',
            'general_section'      => 'Informations générales',
            'name_label'           => 'nom',
            'name_comment'         => 'Nom de l\'enquête.',
            'role_label'           => 'rôle',
            'role_comment'         => 'Rôle associé à l\'enquête.',
            'planning_section'     => 'Déroulement de l\'enquête',
            'duration_label'       => 'durée de validité',
            'duration_comment'     => 'Durée laissée aux participants pour répondre à l\'enquête.',
            'start_date_label'     => 'date de début',
            'start_date_comment'   => 'Date de début de l\'enquête.',
            'end_date_label'       => 'date de fin',
            'end_date_comment'     => 'Date de fin de l\'enquête.',
            'participants_section' => 'Participants de l\'enquête',
            'status_sent'          => 'Invitation envoyée',
            'status_resent'        => 'Rappel envoyé',
            'status_completed'     => 'Enquête complétée',
            'status_pending'       => 'En attente',
            'remind_button'        => 'Envoyer un rappel',
            'remind_success'       => 'Des emails de rappel ont été envoyés aux participants.',
            'refresh_success'      => 'Les statuts des participants ont bien éte mis à jour.',
            'refresh_error'        => 'Les statuts des participants n\'ont pas pu être mis à jour correctement.',
            'remind_error'         => 'Les emails de rappel n\'ont pas pu être envoyé correctement.',
        ],
        'participants' => [
            'record_name'              => 'participant',
            'fullname_column'          => 'Nom et prénom',
            'fn_column'                => 'Prénom',
            'sn_column'                => 'Nom',
            'email_column'             => 'Email',
            'role_column'              => 'Rôle',
            'status_column'            => 'Statut',
            'fn_label'                 => 'prénom',
            'fn_comment'               => 'Prénom du participant.',
            'sn_label'                 => 'nom',
            'sn_comment'               => 'Nom du participant.',
            'email_label'              => 'email',
            'email_comment'            => 'Email à laquelle envoyer l\'enquête.',
            'uid_label'                => 'identifiant unique',
            'uid_comment'              => 'Identifiant unique du participant.',
            'role_label'               => 'rôle',
            'role_comment'             => 'Rôle du participant.',
            'return_button'            => 'Retour',
            'required_section_label'   => 'Attributs obligatoires',
            'required_section_comment' => 'Modifiez les attributs obligatoires du participant.',
            'custom_section_label'     => 'Attributs personnalisés',
            'custom_section_comment'   => 'Sélectionnez les attributs personnalisés ci-dessous pour les visualiser ou les mettre à jour.'
        ],
        'questions'    => [
            'record_name'      => 'question',
            'name_column'      => 'Nom de la question',
            'link_column'      => 'Liaison',
            'name_label'       => 'nom',
            'name_comment'     => 'Nom de la question',
            'question_label'   => 'question associée',
            'question_comment' => 'Les réponses de cette question seront dupliquées.'
        ],
        'columns'      => [
            'record_name'    => 'colonne',
            'name_column'    => 'Nom de colonne',
            'field_column'   => 'Champ obligatoire',
            'type_column'    => 'Type de donnée',
            'name_label'     => 'nom',
            'name_comment'   => 'Nom de la colonne.',
            'type_label'     => 'type de donnée',
            'type_comment'   => 'Type de donnée de la colonne.',
            'field_label'    => 'champ associé',
            'field_comment'  => 'Champ obligatoire associé à cette colonne.',
            'text_option'    => 'Texte',
            'numeric_option' => 'Numérique',
            'date_option'    => 'Date (jj-mm-aaaa)'
        ],
        'attributes'   => [
            'record_name'   => 'attribut',
            'name_column'   => 'Nom de l\'attribut',
            'value_column'  => 'Valeur de l\'attribut',
            'name_label'    => 'nom',
            'name_comment'  => 'Nom de l\'attribut.',
            'value_label'   => 'valeur',
            'value_comment' => 'Valeur de l\'attribut.',
            'type_label'    => 'type de donnée'
        ]
    ]
];