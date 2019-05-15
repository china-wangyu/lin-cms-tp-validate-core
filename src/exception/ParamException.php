<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/5/3
 * Time: 23:57
 */

namespace LinCmsTp\exception;


class ParamException extends \LinCmsTp5\exception\ParameterException
{
    public $code = 400;
    public $message = '参数错误';
    public $error_code = 66667;
}