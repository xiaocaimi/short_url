<!doctype html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="短网址提供短网址在线生成器,短网址还原等在线工具,方便让你域名缩短与转换,更利于用户输入。">
        <meta name="keywords" content="短网址在线生成,短网址还原,短网址生成">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>短网址 - dwz.lt</title>
        <!-- Set render engine for 360 browser -->
        <meta name="renderer" content="webkit">
        <!-- No Baidu Siteapp-->
        <meta http-equiv="Cache-Control" content="no-siteapp"/>
        <!-- Add to homescreen for Chrome on Android -->
        <meta name="mobile-web-app-capable" content="yes">
        <!-- Add to homescreen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Amaze UI"/>
        <meta name="msapplication-TileColor" content="#0e90d2">
        <link rel="stylesheet" href="<?php echo __PUBLIC__;?>css/amazeui.min.css">
        <link rel="stylesheet" href="<?php echo __CSS__;?>style.css">
    </head>
    <body>
        <a href="https://github.com/xiaocaimi/short_url" target="_blank">
            <img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/38ef81f8aca64bb9a64448d0d70f1308ef5341ab/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png">
        </a>
        
        <div class="header">
            <div class="am-g">
                <h1>LT 短网址</h1>
                <p>短网址生成服务<br/>基于发号加hash id的短网址服务</p>
            </div>
            <hr>
        </div>

        <div class="am-g">
            <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
                <form class="am-form">
                    <input type="text" name="" id="url" value="" placeholder="请在此填写你要转换的长网址或短址">
                    <br>
                    <div class="am-cf">
                        <input type="hidden" id="formhash" value="<?php echo formHash();?>" class="am-btn am-btn-primary am-btn-sm am-fl">
                        <input type="button" id="shorten" value="转换短址" class="am-btn am-btn-primary am-btn-sm am-fl">
                        <input type="button" id="expand" value="还原短址" class="am-btn am-btn-default am-btn-sm am-fr">
                    </div>
                </form>
                <hr>
                <p>© <?php echo date('Y'); ?> <a href="http://dwz.lt/">dwz.lt</a> / <a href="<?php echo __WEB__;?>api.html">API</a> . Licensed under MIT license.<span class="float_r">查看其他作品</span></p>
            </div>
        </div>

        <div class="am-modal am-modal-alert" tabindex="-1" id="error_alert" style="width:300px;margin-left:-100px;">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" style="background-color:#E6E6E6;height:40px;padding:0px;line-height:40px;">错误提示</div>
                <div class="am-modal-bd" style="padding-top:10px;">
                </div>
                <div class="am-modal-footer">
                    <span class="am-modal-btn">确定</span>
                </div>
            </div>
        </div>

        <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="loading_shorten" style="width:200px;margin-left:-100px;">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" id="loading_shorten_title">正在生成短网址...</div>
                <div class="am-modal-bd" id="loading_shorten_value">
                    <span class="am-icon-spinner am-icon-spin"></span>
                </div>
            </div>
        </div>

        <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="loading_expand" style="width:200px;margin-left:-100px;">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" id="loading_expand_title">正在还原短网址...</div>
                <div class="am-modal-bd" id="loading_expand_value">
                    <span class="am-icon-spinner am-icon-spin"></span>
                </div>
            </div>
        </div>


        <!--[if (gte IE 9)|!(IE)]><!-->
        <script src="<?php echo __PUBLIC__?>js/jquery.2-1-4.min.js"></script>
        <!--<![endif]-->
        <!--[if lte IE 8 ]>
        <script src="<?php echo __PUBLIC__?>js/jquery.1-11-3.min.js"></script>
        <script src="<?php echo __PUBLIC__?>js/modernizr.js"></script>
        <script src="<?php echo __PUBLIC__?>js/amazeui.ie8polyfill.min.js"></script>
        <![endif]-->
        <script src="<?php echo __PUBLIC__?>js/amazeui.min.js"></script>
        <script src="<?php echo __PUBLIC__?>js/validator.min.js"></script>
        <script src="<?php echo __JS__?>index.js"></script>
    </body>
</html>


