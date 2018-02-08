<?php

return [
    'plugin' => [
        'name' => 'Contenus',
        'description' => 'Ajoute des fonctionnalités pour la Business Intelligence.',
        'permissions' => [
            'access_pages' => 'Gérer les pages',
            'access_visualizations' => 'Gérer les visualisations',
            'access_collections' => 'Gérer les collections',
            'preview_collections' => 'Visualiser les collections',
            'access_folders' => 'Gérer les dossiers',
            'access_focus' => 'Gérer les focus',
            'access_images' => 'Gérer les images',
            'access_snippets' => 'Gérer les snippets'
        ],
        'menus' => [
            'pages' => 'Pages',
            'dashboards' => 'Visualisations',
            'collections' => 'Collections',
            'folders' => 'Dossiers',
            'focus' => 'Focus',
            'datasources' => 'Sources de données',
            'images' => 'Images',
            'snippets' => 'Snippets',
            'static' => 'Données statiques'
        ]
    ],

    'components' => [
        'page' => 'Page',
        'page_description' => 'Affiche une page de Business Intelligence.',
        'id' => 'Page ID',
        'id_description' => 'L\'identifiant de la page',
        'id_validation' => 'L\'identifiant doit être un nombre!',
        'redirect' => 'Rediriger vers',
        'redirect_description' => 'Redirection si l\'utilisateur n\'a pas accès à la page',
        'redirect_page' => 'Page de redirection',
        'redirect_page_description' => 'Lien vers le contenu.',
        'sorting_asc' => 'Du plus ancien au plus récent',
        'sorting_desc' => 'Du plus récent au plus ancien',
        'sorting' => 'Ordre d\'affichage',
        'sorting_description' => 'Ordre d\'affichage des contenus.',
        'pagination' => 'Contenus par page',
        'pagination_description' => 'Nombre de contenus à afficher par page.',
        'pagination_validation' => 'Cela doit être un nombre!',
        'folders' => 'Dossiers',
        'folders_description' => 'Affiche des dossier de BiPages.',
        'focus' => 'Focus',
        'focus_description' => 'Affiche les pages mises en avant.',
        'showcase' => 'Pages publiques',
        'showcase_description' => 'Affiche des pages de Business Intelligence qui sont publiques.',
        'display_mode' => 'Mode d\'affichage',
        'display_mode_description' => 'Le mode d\'affichage du composant.',
        'slide_mode' => 'Diaporama',
        'list_mode' => 'Liste',
        'bookmarks' => 'Liste de favoris',
        'bookmarks_description' => 'Affiche la liste des pages favorites de l\'utilisateur connecté.',
        'subscriber' => 'Bouton favoris',
        'subscriber_description' => 'Permet aux utilisateurs d\'ajouter une page à ses favoris.',
        'unsubscribe_button' => 'Se désabonner',
        'subscribe_button' => 'S\'abonner',
        'search' => 'Barre de recherche',
        'search_description' => 'Recherche des pages de BI à partir de mots clés.'
    ],

    'backend' => [
        'backend_users' => [
            'relation_tab' => 'Collections',
            'collections_section_label' => 'Collections autorisées',
            'collections_section_comment' => 'Spécifier les collections accessibles pour cet administrateur.'
        ],
        'portals' => [
            'folders_section_label' => 'Dossiers du portail',
            'folders_section_comment' => 'Organisez l\'ordre d\'affichage des dossiers par glisser-déposer.',
            'folders_tab' => 'Dossiers'
        ],
        'focus' => [
            'description' => 'Cliquez sur un portail et choisissez les pages à mettre en avant.',
            'focus_column' => 'Pages mises en avant',
            'portal_column' => 'Portail',
            'update_column' => 'Dernière modification',
            'focus_section_label' => 'Pages mises en avant',
            'focus_section_comment' => 'Choisissez les pages qui seront mises en avant sur ce portail.'
        ],
        'pages' => [
            'record_name' => 'Pages',
            'description' => 'Cliquez sur une page pour éditer ses propriétés (titre, description, commentaire...).',
            'name_column' => 'Nom',
            'collection_column' => 'Collection',
            'published_column' => 'Publiée',
            'date_column' => 'Dernière modification',
            'visualizations_section_label' => 'Visualisations de la page',
            'visualizations_section_comment' => 'Ajoutez des visualisations à la page ou modifiez leurs descriptions en cliquant dessus.',
            'visualizations_tab' => 'Visualisations',
            'name_label' => 'Titre',
            'name_comment' => 'Titre de la page.',
            'excerpt_label' => 'Résumé',
            'excerpt_comment' => 'Texte affiché dans les miniatures des focus.',
            'collection_label' => 'Collection',
            'collection_comment' => 'Collection à laquelle rattacher la page.',
            'published_label' => 'Publiée',
            'published_comment' => 'Indique si la page est visible aux internautes.',
            'shared_label' => 'Contenu mutualisé',
            'shared_comment' => 'Restreindre l\'accès aux utiliateurs par portail.',
            'thumbnail_label' => 'Miniature',
            'thumbnail_comment' => 'Image affichée dans les miniatures des focus (720px/420px).'
        ],
        'images' => [
            'record_name' => 'Images',
            'name_column' => 'Nom',
            'collection_column' => 'Collection',
            'date_column' => 'Dernière modification',
            'name_label' => 'Nom',
            'name_comment' => 'Nom de l\'image',
            'source_label' => 'Source',
            'source_comment' => 'Insérez votre image ci-dessous.',
            'collection_label' => 'Collection',
            'collection_comment' => 'Collection de l\'image.'
        ],
        'snippets' => [
            'record_name' => 'Snippets',
            'name_column' => 'Nom',
            'collection_column' => 'Collection',
            'date_column' => 'Dernière modification',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du snippet.',
            'collection_label' => 'Collection',
            'collection_comment' => 'Collection du snippet.',
            'code_label' => 'Code HTML',
            'code_comment' => 'Insérez votre code HTML ci-dessous.',
            'public_label' => 'Snippet publique ?',
            'public_comment' => 'Indique si ce snippet est accessible aux visiteurs.',
            'preview_label' => 'Prévisualisation',
            'preview_comment' => 'Le résultat de votre code HTML s\'affiche ci-dessous.'
        ],
        'dashboards' => [
            'record_name' => 'Visualisation',
            'name_column' => 'Nom',
            'type_column' => 'Type',
            'date_column' => 'Dernière modification',
            'name_label' => 'Titre',
            'name_comment' => 'Titre du contenu.',
            'subtitle_label' => 'Sous-titre',
            'subtitle_comment' => 'Sous-titre du contenu.',
            'description_label' => 'Description',
            'description_comment' => 'Saisissez une description.',
            'source_label' => 'Source',
            'source_comment' => 'Source du contenu.',
            'help_label' => 'Aide',
            'help_comment' => 'Saisissez le texte d\'aide.',
            'collection_label' => 'Collection'
        ],
        'collections' => [
            'record_name' => 'Collections',
            'description' => 'Visualisez vos collections en cliquant dessus et synchronisez les.',
            'name_column' => 'Nom',
            'description_column' => 'Description',
            'pages_column' => 'Pages',
            'dashboards_column' => 'Visualisations',
            'date_column' => 'Dernière modification',
            'name_label' => 'Nom',
            'name_placeholder' => '',
            'name_comment' => 'Nom de la collection.',
            'description_label' => 'Description',
            'description_placeholder' => '',
            'description_comment' => 'Description de la collection',
            'pages_tab' => 'Pages',
            'pages_section_label' => 'Pages de la collection',
            'pages_section_comment' => 'Les pages ci-dessous sont rattachées à cette collection.',
            'visualizations_tab' => 'Visualisations',
            'visualizations_section_label' => 'Visualisations de la collection',
            'visualizations_section_comment' => 'Choisissez les visualisations accessibles pour cette collection.',
            'statistics_section' => 'Statistiques',
            'statistics_empty' => 'Aucune statistique disponible...'
        ],
        'folders' => [
            'record_name' => 'Dossiers',
            'name_column' => 'Nom',
            'pages_column' => 'Pages',
            'description_column' => 'Description',
            'date_column' => 'Dernière modification',
            'pages_tab' => 'Pages',
            'pages_label' => 'Pages du dossier',
            'pages_section_comment' => 'Sélectionnez les pages à ajouter au dossier.',
            'pages_section_label' => 'Pages du dossier',
            'name_label' => 'Nom',
            'name_comment' => 'Nom du dossier.',
            'description_label' => 'Description',
            'description_comment' => 'Description du dossier.',
            'portal_label' => 'Portail',
            'portal_comment' => 'Portail du dossier',
            'published_pages' => 'Publiées',
            'unpublished_pages' => 'Non publiées',
            'statistics_section' => 'Statistiques',
            'statistics_empty' => 'Aucune statistique disponible...'
        ]
    ]
];