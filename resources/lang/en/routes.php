<?php

return [


    'hello' => '/Hello',
    'users' => [
        'create' => '/sign-in',
        'forgotPassword' => '/forgot-password',
        'resetPasswordForm' => '/forgot-password/:token',
    ],
    'dashboard' => [
        'index' => '/dashboard',
        'dancer' => '/dashboard/my-dancers',
        'music' => '/dashboard/music',
        'userEdit' => '/dashboard/person-in-charge',
        'organization' => '/dashboard/my-school',
        'subscriptionsShow' => '/dashboard/my-registrations/:id',
        'routineCreate' => '/dashboard/my-registrations/:id/new-routine',
        'routineEdit' => '/dashboard/my-registrations/:id/edit-routine/:routine_id',
        'routineDuplicate' => '/dashboard/my-registrations/:id/duplicate-routine/:routine_id'
    ],
    'admin' => [
        'index' => '/admin/dashboard',
        'event' => '/admin/dashboard/:event',
        'routineCreate' => '/admin/dashboard/:event/registrations/:id/create-routine',
        'routineEdit' => '/admin/dashboard/:event/registrations/:id/edit-routine/:routine_id',
        'routineDuplicate' => '/admin/dashboard/:event/registrations/:id/duplicate-routine/:routine_id',
        'subscription' => '/admin/dashboard/:event/registrations/:subscription_id',
        'schedule' => '/admin/dashboard/:event/schedule',
        'scheduleOrderPosition' => '/admin/dashboard/:event/schedule-position',
        'scheduleHour' => '/admin/dashboard/:event/schedule-hour',
    ],

];
