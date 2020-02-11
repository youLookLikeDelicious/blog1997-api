<?php
// 过滤富文本
if(!function_exists('removeXSS')){
    function removeXss($data){
        require_once app_path('Library/HTMLPurifier.auto.php');
        $_clean_xss_config = HTMLPurifier_Config::createDefault();
        $_clean_xss_config->set('Core.Encoding', 'UTF-8');
        // 设置保留的标签
        $_clean_xss_config->set('HTML.Allowed','div[style],b,strong,i,em,ul,ol,li,p[style],br,span[style],img[src|width|height|style],code,pre');
        $_clean_xss_config->set('CSS.AllowedProperties', 'font,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align,width,height');
        $_clean_xss_config->set('HTML.TargetBlank', TRUE);
        $_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
        // 执行过滤
        return $_clean_xss_obj->purify($data);
    }
}
