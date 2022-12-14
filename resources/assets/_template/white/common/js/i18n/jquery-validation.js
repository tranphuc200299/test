
$.extend( $.validator.messages, {
    required: trans('message.validate.required'),
    remote: trans('message.validate.remote'),
    email: trans('message.validate.email'),
    url: trans('message.validate.url'),
    date: trans('message.validate.date'),
    dateISO: trans('message.validate.dateISO'),
    number: trans('message.validate.number'),
    digits: trans('message.validate.digits'),
    creditcard: trans('message.validate.creditcard'),
    equalTo: trans('message.validate.equalTo'),
    extension: trans('message.validate.extension'),
    maxlength: $.validator.format( trans('message.validate.maxlength') ),
    minlength: $.validator.format( trans('message.validate.minlength') ),
    rangelength: $.validator.format( trans('message.validate.rangelength') ),
    range: $.validator.format( trans('message.validate.range') ),
    step: $.validator.format( trans('message.validate.step') ),
    max: $.validator.format( trans('message.validate.max') ),
    min: $.validator.format( trans('message.validate.min') ),
    greaterThanZero: trans('message.validate.greaterThanZero'),
    katakana: trans('message.validate.katakana'),
    checkPhone: trans('message.validate.checkPhone'),
    withoutSpace: trans('message.validate.withoutSpace'),
    requiredEmail: trans('message.validate.requiredEmail'),
    requiredName: trans('message.validate.requiredName'),
    requiredPhone: trans('message.validate.requiredPhone'),
    requiredPassword: trans('message.validate.requiredPassword'),
    requiredTypeParty: trans('message.validate.requiredTypeParty'),
} );

