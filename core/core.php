 <?php
// +----------------------------------------------------------------------
// | Author: XiaoCaiMi <info@xiaocaimi.org> <https://www.xiaocaimi.org>
// +----------------------------------------------------------------------
// + 核心类

class core {
    protected $className;
    protected $functionName;
    protected $viewData;

    // 构造函数
    public function __construct($class,$func) 
    {
        $this->className = $class;
        $this->functionName = $func;

        // 调用类中的方法
        if( !method_exists($this,$func) ){
            if( DEBUG ){
                echo '调用 '.$class.' 类中的方法 '.$func.'() 不存在';
            }else{
                echo 'Error:语法错误';
            }
        }else{
            $this->$func();
        }
    }

    // 载入模板变量
    protected function assign($name,$value='')
    {
        $this->viewData[$name] = $value;
    }

    // 载入模板
    protected function display($name='')
    {
        $name = $name ?: ( is_dir(TEMPLATE_PATH.$this->className) ? $this->className.'/'.$this->functionName : $this->functionName);
        if( file_exists(TEMPLATE_PATH.$name.'.php') ){
            foreach( $this->viewData as $key=>$val ){
                $$key = $val;
            }
            include TEMPLATE_PATH.$name.'.php';exit;
        }else{
            if( DEBUG ){
                echo $name.'.php 模板文件不存在';exit;
            }else{
                echo 'Error:语法错误';
            }
        }
    }

    /**
     * 字段验证
*    * @param string $type 验证类型 empty 是否空，url 是否url，email 是否email，phone 是否手机,number 是整数,regex 是正则 
     * @param string $val 字段值
     * @param string $key 字段名 
     * @param string $preg 正则表达式 
     */
    protected function validate($type,$val,$key='',$preg='')
    {
        $msg = _validate($type,$val,$key,$preg);

        if($msg){
            if( IS_AJAX ){
                getJson($msg,'error');
            }else{
                $this->error($msg);
            }
        }
    }

    /**
     * 信息弹出框
     * @param string $msg 是否伪静态链接 
     */
    protected function alert($msg,$display='') 
    {
        echo "<script>alert(\"$msg\");</script>";
        if( $display ){
            $this->display($display);
        }
    } 

    /**
     * 连接跳转
     * @param string $url 跳转地址 
     * @param string $param html参数 
     * @param string $write 是否伪静态链接 
     */
    protected function redirect($url,$param='',$write=false) 
    {
        // 判断url 是否是网址
        if( !_validate('url',$url) ){
            header("Location:".$url);
        }elseif( $write ){
            header("Location:".$url);
        }else{
            $param = $param != '' ? '&'.$param : '';
            // 判断是否有分隔符
            $arr = explode('/',$url);
            if( $arr && count($arr) === 1 ){
                header("Location:".__APP__."?op=".$arr[0].$param);
            }elseif( $arr && count($arr) === 2 ){
                header("Location:".__APP__."?op=".$arr[0]."&method=".$arr[1].$param);
            }elseif( $arr && count($arr) === 3 ){
                header("Location:{$arr[0]}.php?op=".$arr[0]."&method=".$arr[1].$param);
            }else{
                header("Location:".__APP__."?op=".$this->className."&method=".$this->functionName.$param);
            }
        }
        exit;
    } 

    /**
     * 载入成功模板
     * @param string $info 提示信息 
     */
    protected function success($info='成功',$url='',$time=1) 
    {
        // 判断url 是否是网址
        if( !_validate('url',$url) ){
            $url = $url;
        }else{
            $arr = explode('/',$url);
            if( $arr && count($arr) === 1 ){
                $url = __APP__.'?op='.$arr[0];
            }elseif( $arr && count($arr) === 2 ){
                $url = __APP__.'?op='.$arr[0].($arr[1] ? '&method='.$arr[1] : '');
            }elseif( $arr && count($arr) === 3 ){
                $url = $arr[0].'?op='.$arr[1].($arr[2] ? '&method='.$arr[2] : '');
            }else{
                $url = $url ;
            }
        }
        
        if( IS_AJAX ){
            getJson($info,'success',$url);
        }else{
            include TEMPLATE_PATH.'success.php';exit;
        }
    }

    /**
     * 载入错误模板
     * @param string $info 提示信息 
     */
    protected function error($info='失败',$url='',$time=3) 
    {
        if( IS_AJAX ){
            getJson($info,'error',$url);
        }else{
            include TEMPLATE_PATH.'error.php';exit;
        }
    }
}
         

