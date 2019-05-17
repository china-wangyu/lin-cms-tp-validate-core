<?php
/**
 * Created by User: wene<china_wangyu@aliyun.com> Date: 2019/5/17 Time: 10:00
 */

namespace LinCmsTp;

use LinCmsTp\exception\ParamException;
use LinCmsTp\reflex\Reflex;
use LinCmsTp\validate\Param as Permission;
/**
 * Class Param 检验路由的参数
 * @package LinCmsTp\middleware
 */
class Param
{
    /**
     * @var mixed 验证规则或者验证模型
     */
    protected $rule = [];
    /**
     * @var array 验证参数名称定义
     */
    protected $field = [];

    // 设置默认路径
    protected $default_path = 'api/validate';
    // @param 模式
    protected $param = ['name'=>'param','rule'=>['name','doc','rule']];
    // @validate 模式
    protected $validate = ['name'=>'validate','rule'=>['validateModel']];

    private $ext = '.php';

    /**
     * 权限验证
     * @param \think\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ParamException
     */
    public function handle(\think\Request $request, \Closure $next)
    {
        $this->setReflexParamRule($request);
        $auth = (new Permission($this->rule,$request,$this->field))->check();
        if (!$auth) {
            throw new ParamException();
        }
        return $next($request);
    }

    public function setReflexParamRule(\think\Request $request):void {
        $controller = lcfirst(str_replace('.',DIRECTORY_SEPARATOR,$request->controller()));
        $class = env('APP_NAMESPACE').DIRECTORY_SEPARATOR.$request->module().DIRECTORY_SEPARATOR.
            config('url_controller_layer').DIRECTORY_SEPARATOR.$controller;
        $class = str_replace('/','\\',$class);
        $reflex = new Reflex($class,$request->action());
        $param = $reflex->get($this->param['name'],$this->param['rule']);
        $validate = $reflex->get($this->validate['name'],$this->validate['rule']);
        if (!isset($validate[0]['validateModel'])){
            $this->setParamMode($param);
        }else{
            $this->setValidateMode($validate);
        }
    }

    // 设置@validate模式
    public function setValidateMode(array $validate):void {
        if (substr($validate[0]['validateModel'],0,1) == '/' or substr($validate[0]['validateModel'],0,1) == '\\'){
            $this->rule = $validate[0]['validateModel'];
        }else{
            $validate_root_path = empty(config('lin.validate_root_path')) ? $this->default_path :config('lin.validate_root_path');
            $validateFilePath = env('APP_PATH').$validate_root_path;
            $validateFileMap = $this->getDirPhpFile($validateFilePath);
            $validateFile = $this->getValidateFile($validate[0]['validateModel'],$validateFileMap);
            if ($validateFile == null) return;
            $this->rule = str_replace(env('APP_PATH'),env('APP_NAMESPACE').'/',trim($validateFile,$this->ext));
        }
        $this->rule = str_replace('/','\\',$this->rule);
    }
    // 获取验证器文件
    public function getValidateFile(string $validateModel,array $validateFileMap = []):?string {
        foreach ($validateFileMap as $item){
            if (strtolower(basename($item)) !== strtolower($validateModel.$this->ext)) continue;
            return $item;
        }
        return null;
    }
    // 获取文件夹下所有 $ext 的文件
    public function getDirPhpFile(string $dir):array {
        $validateFileMap = [];
        foreach (scandir($dir) as $index => $item){
            if (strstr($item,$this->ext) !== false){
                array_push($validateFileMap,$dir.'/'.$item);continue;
            }
            if (strstr($item,'.')!=false)  continue;
            $validateFileMap = array_merge($validateFileMap,$this->getDirPhpFile($dir.'/'.$item));
        }
        return $validateFileMap;
    }

    // 设置@param模式
    public function setParamMode(array $param):void {
        foreach ($param as $item){
            $this->setField($item['name'],$item['doc']);
            $this->setRule($item['name'],$item['rule']);
        }
    }

    /**
     * @return array
     */
    public function setField($key,$val)
    {
        return $this->field[$key] = $val;
    }

    /**
     * @return array
     */
    public function setRule($key,$val)
    {
        return $this->rule[$key] = $val;
    }
}