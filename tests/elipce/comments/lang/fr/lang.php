<?php

return [
    'plugin' => [
        'name' => 'Commentaires',
        'description' => 'Autorise les utilisateurs à commenter ou discuter des contenus.',
        'permissions' => [
            'access_comments' => 'Gérer les commentaires',
            'access_backend' => 'Gérer les paramètres'
        ]
    ],

    'components' => [
        'last_comments' => 'Derniers commentaires',
        'last_comments_description' => 'Affiche les derniers commentaires du portail courant.',
        'pagination' => 'Commentaires par page',
        'pagination_description' => 'Nombre de commentaires à afficher par page.',
        'pagination_validation' => 'Cela doit être un nombre!',
        'sorting' => 'Ordre d\'affichage',
        'sorting_description' => 'Ordre d\'affichage des commentaires.',
        'sorting_asc' => 'Du plus ancien au plus récent',
        'sorting_desc' => 'Du plus récent au plus ancien',
        'redirect_page' => 'Page de redirection',
        'redirect_page_description' => 'Lien vers la source.',
        'comments' => 'Commentaires',
        'comments_description' => 'Affiche les commentaires et un formulaire pour commenter.',
        'slug' => 'Slug',
        'slug_description' => 'Cherche les commentaires associés à ce slug.',
        'depth' => 'Profondeur',
        'depth_description' => 'Profondeur des fils de discussion',
        'depth_validation_message' => 'La profondeur ne peut contenir que des symboles numériques !'
    ],

    'backend' => [
        'content' => 'Contenu',
        'author' => 'Auteur',
        'name' => 'Nom',
        'created' => 'Créé le',
        'hide' => 'Cacher',
        'published' => 'Visible',
        'show' => 'Montrer',
        'delete_confirm' => 'Êtes-vous sûr?',
        'chart_total' => 'Total',
        'chart_hidden' => 'Cachés',
        'chart_published' => 'Publiés',
        'portal_filter' => 'Portail',
        'page_filter' => 'Page',
        'commented_label' => 'Commentaires',
        'commented_comment' => 'Activer / désactiver les commentaires.'
    ],

    'messages' => [
        'create_error' => 'Erreur lors de la création du commentaire !',
        'edit_error' => 'Erreur lors de la mise à jour du commentaire !',
        'edit_success' => 'Le commentaire a été mis à jour'
    ]
];