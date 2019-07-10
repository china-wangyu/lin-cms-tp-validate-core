<?php
/**
 * Created by User: wene<china_wangyu@aliyun.com> Date: 2019/5/17 Time: 10:00
 */

namespace LinCmsTp\exception;


class ParamException extends \Exception
{
    public $code = 400;
    public $message = '参数错误';
    public $error_code = 99999;

    public function __construct($params = [])
    {
        isset($params['code']) && $this->code = $params['code'];
        isset($params['message']) && $this->message = $params['message'];
        isset($params['error_code']) && $this->error_code = $params['error_code'];
        if(class_exists('\LinCmsTp5\exception\BaseException')){
            throw  new \LinCmsTp5\exception\BaseException([
                'code' => $this->code,
                'msg' => $this->message,
                'error_code' => $this->error_code,
            ]);
        }
        throw new \Exception( $this->error_code.$this->message,$this->code);
    }
}