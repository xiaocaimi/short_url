<?php
// +----------------------------------------------------------------------
// | Author: XiaoCaiMi <info@xiaocaimi.org> <https://www.xiaocaimi.org>
// +----------------------------------------------------------------------
// + 自定义公共函数
// +----------------------------------------------------------------------

// 验证post来源
function signPostDomain($doamin){
	if( parse_url($_SERVER['HTTP_ORIGIN'])['host'] == parse_url($doamin)['host'] ){
		return true;
    }else{
    	return false;
    }
}

// 验证Api IP来源
function getApiDomain($domain){
	if( $domain != '*' ){
		// 来源ip
		$ip = getClientIp();
		// 获取域名ip地址
		$domainIp = gethostbyname($domain);

		if( $ip == $domainIp ){
			return true;
	    }else{
	    	return false;
	    }
	}else{
		return true;
	}
}

// 生成 formHash
function formHash(){

	$time = time();
	$rand = mt_rand(0,99999999);
	$hash = substr(md5($time.'formHash'.$rand),8,16);

	// 读取formHash文件
	$formHash = FC('formHash');
	// 组合新的数组
	$formHash[$hash] = array('time'=>$time,'rand'=>$rand);
 	// 生成新缓存
	FC('formHash',$formHash);
	
	return $hash;
}

// 判断formHash
function signFormHash($formHash){
	$data = FC('formHash')[$formHash];

	if( $formHash == substr(md5($data['time'].'formHash'.$data['rand']),8,16) ){
		return true;
	}else{
		return false;
	}
}

// 生成hash
function hashCode($string,$type){
	if( $type == 'encode' || $type == 'decode' ){
		global $config;

		include_once __ROOT__.'core/Hashids.php';
		$hashIds = new Hashids(
	            $config['hash']['salt'],
	            $config['hash']['length'],
	            $config['hash']['alphabet']
	        );
		$data = $hashIds->$type($string);
		if( $type == 'decode' ){
			return $data ? $data[0] : false;
		}else{
			return $data;
		}
	}else{
		return false;
	}
}

// 判断301跳转，并获取最终的跳转链接
function getLocation($url){
    // 读取缓存
    $realUrl = FC($url,'',array('filePath'=>'realurl','md5'=>true));

    if( $realUrl ){
        return $realUrl;
    }else{
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_exec($ch);
        $curl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        // 记录缓存
        FC($url,$curl,array('filePath'=>'realurl','md5'=>true,'cache_time'=>60*60*24*7));
        return $curl;
    }
}

// 判断域名是否存在
function isSetList($url,$list){
    $url = parse_url($url);
    foreach($list as $val){
        if( strpos($url['host'],$val) !== false ){
            return true;
            break;
        }
    }
    return false;
}

// 判断黑名单、白名单
function getListState($url){
	global $config;
    $curl = getLocation($url);

	// 判断黑名单
    if( $config['list']['type'] == 'black' ){
        $list = $config['list']['black'];
        if( isSetList($url,$list) ){
        	return array('status'=>1,'type'=>'black','realUrl'=>$curl);
        }elseif( isSetList($curl,$list) ){
        	return array('status'=>1,'type'=>'black','realUrl'=>$curl);
        }
    }

    // 判断白名单
    if( $config['list']['type'] == 'white' ){
        $list = $config['list']['white'];
    	if( !isSetList($url,$list) ){
        	return array('status'=>1,'type'=>'white','realUrl'=>$curl);
        }elseif( !isSetList($curl,$list) ){
        	return array('status'=>1,'type'=>'white','realUrl'=>$curl);
        }
    }

    return array('realUrl'=>$curl);
}

// 判断url结果集中关键字是否违法
function blackWord($url){
	global $config;
    $curl = getLocation($url);
    $curl = $url.'/' == $curl ? $url : $curl;

    $postUrl = $config['base_url'].'result?type=blackWord';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $postUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array('url'=>$curl,'realUrl'=>$url))); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_exec($ch);
    curl_close($ch);
}

// 生成Appid 和 Appsecret
function setSign($id,$domain,$type){

	if( $type == 'decode' ){
		$data = DB('short_sign')->find($id);
		if( $data ) return $data;
	}

	include_once __ROOT__.'core/Hashids.php';
	$hashIds = new Hashids('dwz_lt_appid','12');
	$data['appid'] = 'dwz_'.$hashIds->$type($id);

	$hashIds->set('salt','dwz_lt_appsecret');
	$hashIds->set('min_hash_length','32');
	$data['appsecret'] = $hashIds->$type($id);

	$hashIds->set('salt','dwz_lt_name');
	$hashIds->set('min_hash_length','16');
	$data['name'] = $hashIds->$type($id);
	$data['domain'] = $domain;

	if( $type == 'encode' ){
		DB('short_sign')->insert($data);
	}
	return $data;
}

/*
 * 判断签名次数是否超限
 * param array $data sign数据
 */
function getHashSign($data){
	global $config;

	$date = date('Ymd',time());
    $count = DB('short_count')->where(array('sid'=>$data['id'],'date'=>$date,'state'=>0))->count();
    if( $count >= $config['sign'][$data['state']]['key'] && $config['sign'][$data['state']]['key'] != 0 ){
    	return true;
    }else{
    	return false;
    }
}

/*
 * 判断调用次数是否超限
 * param array $data sign数据
 */
function getHashNum($data){
	global $config;
	
	$date = date('Ymd',time());
    $count = DB('short_count')->where(array('sid'=>$data['id'],'date'=>$date,'state'=>1))->count();
    if( $count >= $config['sign'][$data['state']]['num'] && $config['sign'][$data['state']]['num'] != 0 ){
    	return true;
    }else{
    	return false;
    }

}
