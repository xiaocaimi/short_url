<?php
// +----------------------------------------------------------------------
// | Author: XiaoCaiMi <info@xiaocaimi.org> <https://www.xiaocaimi.org>
// +----------------------------------------------------------------------
// + 前台首页
// +----------------------------------------------------------------------

class index extends common {
    // 构造函数
    public function __construct($class,$func) 
    {
        parent::__construct($class,$func);
    }

    // 提交测试
    public function index()
    {
        $this->display();
    }

    // 返回结果 生成/解析短网址
    public function result()
    {
        // 判断是否post提交，并且formhash正确
        if( IS_POST && ( ($_GET['type'] == 'shorten' && signFormHash($_POST['formhash']) || $_GET['type'] == 'expand' ) ) ){
            // 组合操作方法
            $_func = 'getResult'.ucfirst($_GET['type']);
            // 调用组合的操作方法
            $data = $this->$_func($_POST['url'],'');
            echo json_encode($data);
        }elseif( IS_POST && $_GET['type'] == 'blackWord' ){
            global $blackWord;
            // 获取标题和内容
            $str = file_get_contents($_POST['url']);
            foreach( $blackWord as $key=>$val ){
                if( strpos($str,$val) !== false ){
                    // 查询这个url是否存在于数据库
                    DB('short_urls')->where("url='{$_POST['realUrl']}'")->update(array('status'=>2));
                    break;
                }
            }
        }else{
            getJson('不正确的调用','error');
        }
    }

    /* 
     * 调用短网址
     */
    public function hash()
    {
        global $config;

        if( empty($_GET['hash']) ) $this->display('index');
        $id = hashCode($_GET['hash'],'decode');
        if (!$id) {
            $this->alert('短址无法解析','index');
        } else {
            $store = DB('short_urls')->find($id);
            if (!$store) {
                $this->alert('地址不存在','index');
            } else {
                DB('short_urls')->where("id='$id'")->update( array('count'=>'`count`+1') );

                // 判断黑名单、白名单
                $curl = getListState($store['url']);

                if( $store['status'] == '2' || $curl['status'] == '1' ){

                    $this->assign('store',$store);
                    if( $store['url'] != $curl['realUrl'] ) $this->assign('realUrl',$curl['realUrl']);
                    if( $curl['type'] == 'white' ){
                        $this->display('white');
                    }else{
                        $this->display('black');
                    }
                }elseif( $store['status'] == '0' ){
                    $this->alert('此短网址已被禁用！','index');
                }else{
                    $this->redirect($store['url']);
                }
            }
        }
    }

    /* 
     * 生成url短网址
     * $url 要处理的url链接
     */
    private function getResultShorten($url,$sid='')
    {
        global $config;

        if( !parse_url($url)['scheme'] ){
            $url = 'http://'.$url;
        }
        
        // 判断 url
        $this->validate('empty',$url);
        $this->validate('url',$url);

        // 判断来源
        if( !signPostDomain($config['base_url']) ){
            getJson('不正确的调用','error');
        }

        if (strpos(parse_url($url)['host'], parse_url($config['base_url'])['host']) !== false) {
            return array('status'=>0,'info'=>'该地址无法被缩短');
        }else{
            $sha1 = sha1($url);
            $store = DB('short_urls')->where("sha1 = '{$sha1}'")->find();
            if (!$store) {
                $id = DB('short_urls')->insert(array(
                        'sha1'      => $sha1,
                        'url'       => $url,
                        'create_at' => time(),
                        'creator'   => ip2long(getClientIp())
                    ),0);
                $id && blackWord($url);// 判断关键字,并后台执行
            } else {
                $id = $store['id'];
            }
            $short_url = $config['base_url'].hashCode($id,'encode');

            // 写数据库 api生成记录
            $sid != '' && DB('short_count')->insert( array('sid'=>$sid,'date'=>date('Ymd',time()),'state'=>1) );

            return array('status'=>1,'info'=>'success','url'=>$url,'short_url'=>$short_url);
        }
    }

    /* 
     * 解析url短网址
     * $url 要处理的url链接
     */
    private function getResultExpand($url,$sid='')
    {
        global $config;

        if( !parse_url($url)['scheme'] ){
            $url = 'http://'.$url;
        }

        // 判断 url
        $this->validate('empty',$url);
        $this->validate('url',$url);

        // 判断来源
        if( !signPostDomain($config['base_url']) ){
            getJson('不正确的调用','error');
        }

        $hash = str_replace($config['base_url'], '', $url);
        if (!preg_match('/^['.$config['hash']['alphabet'].']+$/', $hash)) {
            return array('status'=>0,'info'=>'短网址不正确');
        } else {
            $id = hashCode($hash,'decode');
            if (!$id) {
                return array('status'=>0,'info'=>'短网址无法解析');
            } else {
                $store = DB('short_urls')->where("id = '{$id}'")->find();

                if (!$store) {
                    return array('status'=>0,'info'=>'地网址不存在');
                } else {
                    // 写数据库 api解析记录
                    $sid!='' && DB('short_count')->insert( array('sid'=>$sid,'date'=>date('Ymd',time()),'state'=>1) );

                    return array('status'=>1,'info'=>'success','url'=>$store['url'],'short_url'=>$url);
                }
            }
        }
    }

    /* 
     * api接口说明
     */
    public function api_index()
    {
        $this->display();
    }

    /* 
     * 获取密钥
     */
    private function getSign()
    {
        if( IS_POST ){
            // 获取appid绑定的域名
            $data = DB('short_sign')->where(array("appid"=>$_POST['appid'],'appsecret'=>$_POST['appsecret']))->find();
            if( !getApiDomain($data['domain']) ){
                getJson('非法的域调用','error');
            }

            // 查询当前域名今日签名调用次数
            if( getHashSign($data) ){
                getJson('签名获取次数超出限制','error');
            }else{
                // 生成密钥
                $key['key'] = $data['name'].sha1($data['name'].$data['appid'].$data['appsecret'].$_POST['time']);
                $key['post_time'] = $_POST['time'];
                $key['status'] = 1;

                // 写数据库 签名调用记录
                DB('short_count')->insert( array('sid'=>$data['id'],'date'=>date('Ymd',time()),'state'=>0) );

                // 写密钥文件
                FC($data['name'],$key);
                echo json_encode($key);
            }
        }else{
            getJson('不正确的调用','error');
        }
    }

    /* 
     * api接口
     */
    public function api()
    {
        // 生成 临时appid
        if( $_GET['getSign'] && $_GET['getSign'] == 'XiaoCaiMi' && !empty($_GET['domain']) ){
            getJson('暂停Api申请接口,后续开放请持续关注！');
            $data = json_encode( setSign(rand(1,100),$_GET['domain'],'encode') );
            echo $data;exit;
        }

        // 获取加密后的key
        if( $_GET['mod'] == 'getSign' ){
            $this->getSign();exit;
        }

        // 判断密钥
        if( $_GET['key'] ){
            $fileName = substr($_GET['key'],0,16);
            $cache = FC($fileName,'',array('time'=>true));

            // 判断缓存文件是否存在，并判断是否过期
            if( $cache && time() - $cache['time'] > 7200 ){
                // 查询sign数据库
                $data = DB('short_sign')->where("name='$fileName'")->find();
                // 判断密钥是否正确
                if( $data['name'].sha1($data['name'].$data['appid'].$data['appsecret'].$cache['post_time']) == $_GET['key'] && $_GET['key'] == $cache['key'] ){

                    // 查询当前域名今日api调用次数
                    if( getHashNum($data) ){
                        getJson('调用次数超出限制','error');
                    }

                    // 写数据库 签名调用记录
                    DB('short_count')->insert( array('sid'=>$data['id'],'date'=>date('Ymd',time()),'state'=>1) );

                    // 组合操作方法
                    $_func = 'getResult'.ucfirst($_GET['mod']);
                    // 调用组合的操作方法
                    $classData = $this->$_func($_GET['url'],$data['id']);
                    // 返回json格式
                    $json = json_encode($classData);

                    if( !empty($_GET['type']) ){
                        getJson($classData);
                    }else{
                        global $config;
                        $this->redirect($config['base_url']);
                    }
                }else{
                    getJson('错误的密钥','error');
                }
            }else{
                getJson('过期/错误的密钥','error');
            }
        }else{
            getJson('密钥不能为空','error');
        }
    }
}

?>
