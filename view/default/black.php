<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>DWZ-跳转提示</title>
        <link rel="stylesheet" href="<?php echo __CSS__;?>error.css">
	</head>

	<body class="error_bg">
		<div class="error_frame ip_error">
			<div class="error_logo"></div>
		    <div class="erroe_icon"></div>
		    <div class="ip_error_text">
		    	<ul class="ip_zh_text">
		    	 	<li><h2>欲访问的网址：<br/><br/><a href="<?php echo $store['url']; ?>" class="blue_text" ><?php echo $store['url']; ?></a></h2><?php if($realUrl != ''){echo '<br/><h2>最终转向网址：<a href="'.$realUrl.'" class="blue_text" >'.$realUrl.'</a></h2>';} ?></li>
		        	<li>&nbsp;</li>
		        	<li>&nbsp;</li>
  					<li><h2>网站疑似含有<span style="color:#E22E1C">赌博、色情、虚假诈骗</span>等违禁内容</h2><br/><h2>请自行判断是否打开！</h2></li>
  					<li>&nbsp;</li>
		            <li><a class="blue_text callcenter" onclick="openKF();">如果该网站没有违禁词汇/虚假诈骗，请点此申诉</a></li>
		        </ul>
		    </div>  
		</div>

		<script>
		    function openKF(){
		    	alert('暂未开通申诉方式，请等待更新');return false;
		    	window.open('跳转地址', '_blank',
		         'height=473,width=703,fullscreen=3,top=200,left=200,status=yes,toolbar=no,menubar=no,resizable=no,scrollbars=no,location=no,titlebar=no,fullscreen=no');
		    	return false;
		    }
		</script>
	</body>
</html>

