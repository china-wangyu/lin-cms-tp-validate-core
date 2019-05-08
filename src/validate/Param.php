<?php


namespace LinCmsTp\validate;

use think\Validate;
use LinCmsTp\exception\ParamException;
class Param
{
    public $rule;
    public $field;
    public $request;

    public function __construct($rule,\think\Request $request,array $field = [])
    {
        $this->request = $request;
        $this->rule = $rule;
        $this->field = $field;
    }

    public function check(){
        if (is_string($this->rule)) {
            $validate = new $this->rule();
        }else{
            $validate = (new Validate())->make($this->rule,[],$this->field);
        }
        $res = $validate->check($this->request->param());
        if(!$res){
            $e = new ParamException([
                'message' => $validate->getError(),
            ]);
            throw $e;
        }
        return true;
    }
}