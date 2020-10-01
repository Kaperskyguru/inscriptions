<?php

return [


    'hello' => '/allo',
    'users' => [
        'create' => '/creer-un-compte',
        'forgotPassword' => '/reinitialiser-le-mot-de-passe',
        'resetPasswordForm' => '/reinitialiser-le-mot-de-passe/:token',
    ],
    'dashboard' => [
        'index' => '/tableau-de-bord',
        'dancer' => '/tableau-de-bord/mes-danseurs',
        'music' => '/tableau-de-bord/ma-musique',
        'userEdit' => '/tableau-de-bord/mon-responsable',
        'organization' => '/tableau-de-bord/mon-ecole',
        'subscriptionsShow' => '/tableau-de-bord/mes-inscriptions/:id',
        'routineCreate' => '/tableau-de-bord/mes-inscriptions/:id/nouvelle-routine',
        'routineEdit' => '/tableau-de-bord/mes-inscriptions/:id/modifier-routine/:routine_id',
        'routineDuplicate' => '/tableau-de-bord/mes-inscriptions/:id/dupliquer-routine/:routine_id'
    ],
    'admin' => [
        'index' => '/admin/tableau-de-bord',
        'event' => '/admin/tableau-de-bord/:event',
        'routineCreate' => '/admin/tableau-de-bord/:event/inscriptions/:id/nouvelle-routine/:organization_id',
        'routineEdit' => '/admin/tableau-de-bord/:event/inscriptions/:id/modifier-routine/:routine_id',
        'routineDuplicate' => '/admin/tableau-de-bord/:event/inscriptions/:id/dupliquer-routine/:routine_id',
        'subscription' => '/admin/tableau-de-bord/:event/inscriptions/:subscription_id',
        'schedule' => '/admin/tableau-de-bord/:event/ordre-affichage',
        'scheduleOrderPosition' => '/admin/tableau-de-bord/:event/ordre-de-passage',
        'scheduleHour' => '/admin/tableau-de-bord/:event/heure-de-passage',
    ],

];
