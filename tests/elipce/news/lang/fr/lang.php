<?php

return [
    'plugin' => [
        'name' => 'Actualités',
        'description' => 'Rédaction d\'actualités par portail.',
        'permissions' => [
            'access_news' => 'Gérer les actualités'
        ]
    ],

    'component' => [
        'news' => 'Actualités',
        'news_description' => 'Affiche les actualités du portail.',
        'pagination' => 'Actualités par page',
        'pagination_description' => 'Le nombre d\'actualités à afficher par page.',
        'pagination_validation' => 'Cela doit être un nombre!',
        'sorting_asc' => 'Du plus ancien au plus récent',
        'sorting_desc' => 'Du plus récent au plus ancien',
        'sorting' => 'Ordre d\'affichage',
        'sorting_description' => 'Ordre d\'affichage des contenus.',
        'redirect_page' => 'Page de redirection',
        'redirect_page_description' => 'Lien vers le contenu.'
    ],

    'backend' => [
        'news' => [
            'record_name' => 'Actualité',
            'name_column' => 'Titre',
            'date_column' => 'Dernière modification',
            'name_label' => 'Titre',
            'name_placeholder' => '',
            'name_comment' => 'Titre de l\'actualité.',
            'text_label' => 'Texte',
            'text_placeholder' => '',
            'text_comment' => 'Contenu de l\'actualité.',
            'page_label' => 'Page',
            'page_comment' => 'Associer une page à l\'actualité (optionel).',
            'page_empty' => 'Aucune',
            'portal_label' => 'Portail',
            'portal_comment' => 'Portail de sur lequel sera visible l\'actualité.',
            'thumbnail_label' => 'Miniature',
            'thumbnail_comment' => 'Image affichée dans les miniatures des actualités (720px/420px).'
        ]
    ]
];

?>