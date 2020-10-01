<?php

return [

    'user' => [
        'notFound' => 'Unable to find user ID',
        'notLoggedin' => 'User is not logged in',
        'errorLogin' => 'The email or password is incorrect',
    ],
    'organization' => [
        'notFound' => 'The organization could not be found',
        'notLoggedin' => 'User is not logged in',
    ],
    'dancer' => [
        'noEdit' => 'You currently can’t edit or delete dancers as one or many of your registration have been submitted. To edit or delete a dancer, you must first make sure that all your registrations are back to edit mode and showing the « Not submitted » status.'
    ],
    'subscription' => [
        'notFound' => 'This registration could not be found',
        'notLoggedin' => 'User is not logged in',
        'submit' => 'We successfully received your submission. We\'re currently reviewing your registration. We will be contacting you shortly with the official invoice. Please note that your registration will only be confirmed once we receive your payment.'
    ],
    'routine' => [
        'soloNovice' => 'In order to register in the Novice level, a solo dancer needs to be 15 years or younger.',
        'elite' => 'In order to register in the Elite level, the average age of the dancers must be 13 years and over.',
        'deleteRoutine' => 'Are you sure you want to delete this routine ?',
        'flofestcategory' => 'In order to register the routine must be a solo or have 4 or more dancers',
        'unqualified' => 'The routine is not in the competition'
    ],
    'global' => [
        'success' => 'Saved successfully!',
        'fail' => 'An error has occurred. Try Again.',
    ],
    'email' => [
        'fail' => 'The email could not be sent to this address.'
    ],
    'password' => [
        'success' => 'Your password has successfully been changed. You can now log in.',
        'fail' => 'Your email is invalid',
        'wrong' => 'Your password is invalid',

    ]
    

];
