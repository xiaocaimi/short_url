<?php
// +----------------------------------------------------------------------
// | Author: XiaoCaiMi <info@xiaocaimi.org> <https://www.xiaocaimi.org>
// +----------------------------------------------------------------------
// + 全局公共函数
// +----------------------------------------------------------------------

// 设置编码 
header("content-type:text/html;charset=utf-8");

/* 开启SESSION */
session_start();

/* 设置时区 */
date_default_timezone_set('Asia/Chongqing');

/* 设置路径常量 - 绝对路径 */
define('__ROOT__',str_replace('\\','/',dirname(__DIR__)).'/');

/* 设置路径常量 - 相对路径 */
define('__WEB__',str_replace('\\','/',dirname($_SERVER['PHP_SELF'])) != '/' ? str_replace('\\','/',dirname($_SERVER['PHP_SELF'])).'/':str_replace('\\','/',dirname($_SERVER['PHP_SELF'])));

/* 设置URL */
define('__URL__','http://'.$_SERVER['HTTP_HOST'].__WEB__);

// 定义入口文件
define('__APP__',str_replace(__WEB__,'',$_SERVER['PHP_SELF']));

// 获取模版名称，如果模版名称未定义,则默认default
$__template__ = __TEMPLATE__ ?: 'default';

/* 设置资源路径 - 相对路径 */
define('__STATIC__',__WEB__.'view/static/');
define('__PUBLIC__',__STATIC__.'public/');
define('__CSS__',__WEB__.'view/static/'.$__template__.'/css/');
define('__JS__',__WEB__.'view/static/'.$__template__.'/js/');
define('__IMG__',__WEB__.'view/static/'.$__template__.'/img/');

// 设置模版路径 - 绝对路径
define('TEMPLATE_PATH',__ROOT__.'view/'.$__template__.'/');

/* 判断是否是 GET 操作*/
define('IS_GET',$_SERVER['REQUEST_METHOD'] =='GET' ? true : false);

/* 判断是否是 POST 操作*/
define('IS_POST',$_SERVER['REQUEST_METHOD'] =='POST' ? true : false);

/* 判断是否是 Ajax 操作 */
define('IS_AJAX',(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false);

/* 载入配置文件 */
include __ROOT__.'config.php';

/* 载入核心类 */
include __ROOT__.'core/core.php';

/* 载入安全设置文件 */
include __ROOT__.'core/safe.php';

/* 屏蔽错误输出 */
if( DEBUG ) {
    error_reporting(E_ALL & ~E_NOTICE);
}else{
    error_reporting(0);
}

$__module__ = __MODULE__ ? __MODULE__.'/': '';

// 加载公共类
@include __ROOT__.'/app/'.$__module__.'common.class.php';

// 载入后自定义共函数
@include __ROOT__.'/app/'.$__module__.'function.php';

/* ++++++++++++ 入口操作，加载指定的类 ++++++++++++++ */

$class = $_GET['op'] ?: 'index';
$func = $_GET['method'] ?: 'index';

if( file_exists(__ROOT__.'app/'.$__module__.'/'.$class.'.class.php') ){
	include __ROOT__.'app/'.$__module__.'/'.$class.'.class.php';
}else{
	echo $__module__.'/'.$class.' 控制器不存在';exit;
}

// 执行操作
new $class($class,$func);exit;


/* +++++++++++++++++++ 功能函数 +++++++++++++++++++ */

/**
 * 输出各种类型的数据，调试程序时打印数据使用 print echo 合称。
 * @param	mixed	参数：可以是一个或多个任意变量或值
 * @author XiaoCaimi <info@xiaocaimi.org>
 */
function PC(){
    $args=func_get_args();  // 获取多个参数
    if(count($args)<1){
        echo "<font color='red'>必须为PC()函数提供参数!";
    }	

    echo '<div style="width:100%;text-align:left"><pre>';
    // 多个参数循环输出
    foreach($args as $kry=>$arg){
        if($args[count($args)-1] == $arg && $arg === true){exit;}
        if(is_array($arg)){  
            print_r($arg);
            echo '<br>';
        }else if(is_string($arg)){
            echo $arg.'<br>';
        }else{
            var_dump($arg);
            echo '<br>';
        }
    }
    echo '</pre></div>';	
}

/**
 * 获取消息存取(写入文本) File Cache
 * @param string $name 文件名 
 * @param string $data 数据内容
 * @param array $param filePath存储路径,必须相对路径、md5是否启用md5,默认为true、cache_time 缓存时间、time 为真获取时间
 */
function FC( $name,$data='',$param=array('filePath'=>'','md5'=>false,'cache_time'=>0,'time'=>false) ) {
    if($name){
        // 判断 $param 参数
        $param['filePath'] = $param['filePath'] ?: '';
        $param['md5'] = $param['md5'] ?: false;
        $param['cache_time'] = $param['cache_time'] ?: 0;
        $param['time'] = $param['time'] ?: false;

        // 判断是否设置存储路径；无论右侧是否有/结尾都先清除，再添加
        $filePath = $param['filePath'] != '' ? rtrim($param['filePath'],'/').'/' : $param['filePath'];
        if( !is_dir(__ROOT__.'cache/'.$filePath) ) mkdir(__ROOT__.'cache/'.$filePath,0777,true);

        // 判断是否需要md5加密
        $name = '#'.($param['md5'] ? substr(md5($name),8,16) : $name);

        // 获取文件具体路径
        $path = __ROOT__.'cache/'.$filePath.$name.'.json';

        if($data === ''){    // 读取
            $res = json_decode( @file_get_contents($path) ,true);
            // 判断文件是否过期
            if( $res && ( time() - $res['time'] < $res['cache_time'] || $res['cache_time'] == 0 ) ){
                if( $param['time'] ){
                    $result['time'] = $res['time'];
                    if( is_array($res['data']) ){
                        $result = $res['data'];
                    }else{
                        $result['data'] = $res['data'];
                    }
                }else{
                    unset($res['time']);
                }
                unset($res['cache_time']);
                return $result ?: $res['data'];
            }else{
                // 文件过期，则删除文件
                @unlink($path);
                return false;
            }
        }elseif(is_null($data)){    // 删除
            @unlink($path);
        }else{  // 生成
            $content['data'] = $data;
            $content['time'] = time();
            $content['cache_time'] = $param['cache_time'];
            @file_put_contents( $path,json_encode($content) );
        }
    }else{
        return false;
    }
}

/**
 * 数据库操作函数
 * @param  string   $tabName 表名	
 * @author XiaoCaimi <info@xiaocaimi.org>
 */ 
function DB($tabName=''){
    include_once(__ROOT__.'core/mysql.php');
    $db = new PdoMySQL(DB_HOST,DB_PORT,DB_USER,DB_PWD,DB_NAME,DB_PREFIX.$tabName); 
    
    return $db;
}

/**
 * 自动验证
 * @param string $type 验证类型 empty 是否空，url 是否url，email 是否email，phone 是否手机,number 是整数,regex 是正则 
 * @param string $val 字段值
 * @param string $key 字段名 
 * @param string/array $preg 正则表达式/判断是否唯一的参数array(表名,返回消息,验证的字段)
 */
function _validate($type,$val,$key='',$preg='') {
    $msg = '';
    $val = trim($val,' ');

    switch( $type ){
        case 'empty':
            if( $val == '' ) $msg = $key.'不能为空！';
            break;
        case 'url':
            if( $val != '' && !preg_match('/^http(s?):\/\/((?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}|(\d+)\.(\d+)\.(\d+)\.(\d+))(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',urlencode_ch($val)) ) $msg = 'url格式错误';
            break;
        case 'email':
            if( $val != '' && !preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$val) ) $msg = '邮箱格式错误';
            break;
        case 'phone':
            if( $val != '' && !preg_match('/^((13[0-9])|(14[0-9])|(15[0-9])|(17[0-9])|(18[0-9]))[0-9]{8}$/',$val) ) $msg = '手机号码格式错误';
            break;
        case 'number':
            if( $val != '' && $val < 0 ) $msg = $key.'不是正数';
            break;
        case 'unique':
            if( $val != '' ){
                $where = "$key = '{$val}'";
                if( !empty($preg[2]) ) $where .= " AND id != {$preg[2]}";
                $result = DB($preg[0])->where($where)->find();
                if( $result ){
                    $msg = $preg[1];
                }
            }
            break;
        case 'regex':
            if( $val != '' && !preg_match($preg,$val) ) $msg = $key;
            break;
    }

    return $msg;
}

/**
 * 返回 json/jsonp 格式
 * @param  string $info 消息
 * @param  string $type 消息类型  success、error
 * @param  string $url  跳转url
 * @return boolean     检测结果
 * @author XiaoCaimi <info@xiaocaimi.org>
 */
function getJson($info,$type,$url = ''){
    if( !empty($_GET['callback']) && !empty($_GET['_']) ){
        $_jsonType_['type'] = 'jsonp';
        $_jsonType_['callback'] = $_GET['callback'];
    }

    $status = $type == 'success' ? 1 : 0;

    if( is_array($info) ){
        $data = $info;
    }else{
        $data['info'] = $info;
        $data['status'] = $status;
        if( !empty($url) ) $data['url'] = $url;
    }
    
    if( $_jsonType_['type'] == 'jsonp' ){
        header("content-type:application/jsonp;charset=utf-8");
        echo $_jsonType_['callback']."(".json_encode($data).")";  
    }else{
        header("content-type:application/json;charset=utf-8");
        echo json_encode($data);
    }
    exit;
}

/**
 * 获取客户端ip
 * @author XiaoCaimi <info@xiaocaimi.org>
 */
function getClientIp($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = ip2long($ip);
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * urlencode网址
 * @author XiaoCaimi <info@xiaocaimi.org>
 */
function urlencode_ch($str){
    return str_replace('%2B','+',str_replace('%26','&',str_replace('%3D','=',str_replace('%3F','?',str_replace('%2F','/',str_replace('%3A%2F%2','://',urlencode($str)))))));
}
