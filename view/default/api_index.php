<!DOCTYPE HTML5>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="content-type" name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width">
        <title>短网址Api接口信息</title>
        <meta name="keywords" content="短网址接口,短网址api">
        <meta name="description" content="短网址生成、解析工具，支持json和jsonp、xml等格式">
        <!-- 代码高亮 -->
        <link  rel="stylesheet" href="<?php echo __CSS__;?>highlight.min.css"/>
        <script type="text/javascript" src="<?php echo __JS__;?>highlight.min.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </head>
    <body>
        <div style='margin:50px 20%;'>Api接口信息（dwz.lt）<br/><br/>
        1、生成短网址（<a href="http://dwz.lt/demo.zip">下载php demo</a>）<br/><br/>
<pre><code>
测试账号：AppId:dwz_2XKpa2naxn5k  AppSecret:oQB4dMW7eXJ8Ava2Ramp61DVE5jGrwbR
(该AppId 每天可以生成50次密钥。每次密钥有效期7200秒,每天可调用500次)
</code></pre><br/>
<pre><code>
http://dwz.lt/api?mod=shorten&type=json&key=加密的密钥&url=原始url

mod为操作方法,shorten生成短网址、expand还原短网址
type为返回类型,如果为空则默认返回json格式。支持json、jsonp
key为加密的密钥,密钥的加密方法为：( key 有效期 7200秒 )
    加密值为：post 数据到 http://dwz.lt/api?mod=getSign 
    post参数为：appid、appsecret、time(时间戳)
    appid、appsecret 是申请到的值



直接get获取即可，例如：
http://dwz.lt/api?mod=shorten&type=json&key=加密的密钥&url=原始url

返回结果为json格式：
    成功：{"status":1,"info":"success","url":"原始url","short_url":"短网址url"}
    失败：{"status":0,"info":'错误原因'}

跨域实例：
    $.getJSON('http://dwz.lt/api?mod=expand&type=json&key=加密的密钥&url=原始url&callback=?',function(data){
        if( data.status == 1 ){
            // 生成成功
        }else{
            // 生成失败
        }
    }) 
</code></pre><br/><br/>
        2、解析短网址<br/><br/>
<pre><code>
http://dwz.lt/api?mod=expand&type=json&key=加密的密钥&url=原始url

mod为操作方法,shorten生成短网址、expand还原短网址
type为返回类型,如果为空则默认返回json格式。支持json、jsonp
key为加密的密钥,密钥的加密方法为：( key 有效期 7200秒 )
    加密值为：post 数据到 http://dwz.lt/api?mod=getSign 
    post参数为：appid、appsecret、time(时间戳)
    appid、appsecret 是申请到的值



直接get获取即可，例如：
http://dwz.lt/api?mod=expand&type=json&key=加密的密钥&url=原始url

返回结果为json格式：
    成功：{"status":1,"info":"success","url":"原始url","short_url":"短网址url"}
    失败：{"status":0,"info":'错误原因'}

跨域实例：
    $.getJSON('http://dwz.lt/api?mod=expand&type=json&key=加密的密钥&url=原始url&callback=?',function(data){
        if( data.status == 1 ){
            // 解析成功
        }else{
            // 解析失败
        }
    }) 
</code></pre>
        </div>
    </body>
</html>

