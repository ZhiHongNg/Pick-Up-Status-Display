<?php
namespace app\api\validate;

use think\Validate;

class CarValidator extends Validate
{
    protected $rule = [
        'plate' => 'require|alphaNum',
        'camId' => 'require|number',
        'time' => 'require|dateFormat:Y-m-d H:i:s',
    ];

    protected $message = [
        'plate.require' => 'The plate field is required',
        'plate.alphaNum' => 'The plate field must be alphanumeric',
        'camId.require' => 'The camId field is required',
        'camId.number' => 'The camId field must be a number',
        'time.require' => 'The time field is required',
        'time.dateFormat' => 'The time field must be in Y-m-d H:i:s format',
    ];
}
