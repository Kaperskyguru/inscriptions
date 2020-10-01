<?php

return [

    'user' => [
        'notFound' => 'Impossible de trouver l\'utilisateur par ID',
        'notLoggedin' => 'Utilisateur n\'est pas connecté',
        'errorLogin' => 'Le courriel ou le mot de passe est incorrect',
    ],
    'organization' => [
        'notFound' => 'Cette organisation n\'existe pas',
        'notLoggedin' => 'Utilisateur n\'est pas connecté',
    ],
    'dancer' => [
        'noEdit' => 'Notez qu’il n’est pas possible de modifier ou supprimer des danseurs puisqu’une ou plusieurs de vos inscriptions ont été soumises. Pour pouvoir modifier ou supprimer un danseur, vous devez d’abord réactiver les modifications de chacune de vos inscriptions afin que celles-ci soient au statut « Non-soumis ».'
    ],
    'subscription' => [
        'notFound' => 'Cette inscription n\'existe pas',
        'notLoggedin' => 'Utilisateur n\'est pas connecté',
        'submit' => 'Nous avons bien reçu la soumission de votre inscription. Votre inscription est présentement en révision. Nous vous contacterons dans les plus brefs délais afin de vous envoyer la facture officielle. Veuillez noter que votre inscription sera confirmée uniquement sur réception de votre paiement. '
    ],
    'routine' => [
        'soloNovice' => 'Afin de pouvoir s\'inscrire dans le niveau Novice, un danseur solo doit être agé de moins de 15 ans',
        'elite' => 'Afin de pouvoir s\'inscrire dans le niveau Elite, la moyenne d\'âge des danseurs doit être de 13 ans et plus',
        'deleteRoutine' => 'Êtes-vous certains de vouloir supprimer cette routine ?',
        'flofestcategory' => 'Afin de pouvoir s\'inscrire, la routine doit être un solo ou avoir 4 danseurs et plus',
        'unqualified' => 'Cette routine ne fait pas partie de la compétition.'
    ],
    'global' => [
        'success' => 'Sauvegarde réussie!',
        'fail' => 'Une erreur est survenue. Veuillez réessayer.',
    ],
    'email' => [
        'fail' => 'Le courriel n\'a pu être envoyé à cette adresse'
    ],
    'password' => [
        'success' => 'Votre mot de passe a bien été modifié. Vous pouvez maintenant vous connecter.',
        'fail' => 'L\'adresse courriel est erronée',
        'wrong' => 'Mauvais mot de passe',

    ]
    

];
