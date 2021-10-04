<?php
return [
    'validate' => [
        'required' =>' This field is required. ',
        'remote' =>' Correct this field. ',
        'email' =>'Please enter the correct email address format',
        'url' =>'Please enter a valid URL. ',
        'date' =>'Please enter a valid date. ',
        'dateISO'=>' Please enter a valid date (ISO). ',
        'number' =>' Please enter a number. ',
        'digits' =>'Enter only numbers. ',
        'creditcard' =>'Please enter a valid credit card number. ',
        'equalTo' =>'Enter the same value again. ',
        'extension' =>'Enter a value that includes a valid extension. ',
        'maxlength' =>'Enter up to {0} characters. ',
        'minlength' =>' Set the password with at least {0} characters. ',
        'rangelength' =>'Enter a value between {0} and {1} characters. ',
        'range' => 'Enter a value between {0} and {1}.',
        'step' => 'Please enter a multiple of {0}.',
        'max' => '{0} Please enter the following values.',
        'min' => 'Enter a value greater than or equal to {0}.',
        'greaterThanZero' => 'Please enter a value greater than 0',
        'katakana' => 'Please enter katakana',
        'deleted' => 'It was erased',
        'checktime' => 'Please enter a valid time.',
        'unique email' => 'The specified email is already in use.',
        'phone' => 'Please enter the correct phone number format.',
        'checkPhone' => 'Please enter half-width',
        'withoutSpace' => 'At least one word is required',
        'password' => 'The password must be 8 to 20 characters in half-width alphanumeric characters and must include all uppercase letters, lowercase letters, and numbers.',
        'confirmPassword' => 'The new password and the re-entered password do not match.。',
        'IPv4Check' => 'Invalid IPV4 address'
    ],
    'notify' => [
        'create success' => 'Create success',
        'update success' => 'Update success',
        'delete success' => 'Delete success',
        'create fail' => 'Creation failed. Please try again',
        'update fail' => 'The change failed. Please try again',
        'delete fail' => 'Deletion failed. Please try again',
        'something went wrong' => 'An error has occurred. Please try again',
        'permission denied' => '403: You do not have access permission',
        'permission when create account' => 'You do not have permission to create this account',
        'ip incorrect, please authenticate again' => 'IP incorrect, please authenticate again',
    ]
];