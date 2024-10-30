<?php
/*
Plugin Name: Lightweight Loading Bar
Description: Add a lightweight YouTube-style loading bar to the top of your site.
Version: 1.4
Author: Sospire Media
Author URI: https://sospire.media?utm_source=wordpress.org&utm_content=wordpresspluginlightweightloadingbar
License: GPLv2 or later
*/

defined('ABSPATH') or die('Access Denied');

if (file_exists(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
}   else {
    $lw_lb_configtext = file_get_contents(__DIR__ . '/assets/configText.php');
    file_put_contents(__DIR__ . '/config.php', $lw_lb_configtext.PHP_EOL , FILE_APPEND | LOCK_EX);
}

// Register complicated global vars without conflict
$lw_lb_enableonmobile = $enable_on_mobile;
$lw_lb_mobilewidth = $mobile_width;
$lw_lb_enableonlogin = $enable_on_login;
$lw_lb_color = $color;
$lw_lb_height = $height;
$lw_lb_isgradient = $gradient;
$lw_lb_startgradient = $start_gradient;
$lw_lb_endgradient = $end_gradient;
$lw_lb_position = $position;

if ($lw_lb_isgradient == 'true') {
    $lw_lb_style = 'linear-gradient(to right,' . $lw_lb_startgradient . ',' . $lw_lb_endgradient . ')';
} else {
    $lw_lb_style = $lw_lb_color;
}


if ($lw_lb_enableonmobile == 'false') {
    $lw_lb_visibility = '@media screen and (max-width:' . $lw_lb_mobilewidth . ') {div#lw-loading-bar {visibility: hidden;}}';
} else {
    $lw_lb_visibility = '';
}



function lw_loading_bar_inject_element() {
    ?>
    <script type="text/javascript">
    (function(root){"use strict";var css="<?php global $lw_lb_visibility; echo $lw_lb_visibility; ?> .lwloadingbar{width:100%;height:<?php global $lw_lb_height; echo $lw_lb_height ?>;z-index:9999;<?php global $lw_lb_position; echo $lw_lb_position ?>:0}.bar{width:0;height:100%;transition:height .3s;background:<?php global $lw_lb_style; echo "$lw_lb_style" ?>}";function addCss(){var s=document.getElementById("lwloadingbarcss");if(s===null){s=document.createElement("style");s.type="text/css";s.id="lwloadingbarcss";document.head.insertBefore(s,document.head.firstChild);if(!s.styleSheet)return s.appendChild(document.createTextNode(css));s.styleSheet.cssText=css}}function addClass(el,cls){if(el.classList)el.classList.add(cls);else el.className+=" "+cls}function createBar(rm){var el=document.createElement("div"),width=0,here=0,on=0,bar={el:el,go:go};addClass(el,"bar");function move(){var dist=width-here;if(dist<.1&&dist>-.1){place(here);on=0;if(width>=100){el.style.height=0;setTimeout(function(){rm(el)},300)}}else{place(width-dist/4);setTimeout(go,16)}}function place(num){width=num;el.style.width=width+"%"}function go(num){if(num>=0){here=num;if(!on){on=1;move()}}else if(on){move()}}return bar}function LWLoadingbar(opts){opts=opts||{};var el=document.createElement("div"),applyGo,lwloadingbar={el:el,go:function(p){applyGo(p);if(p>=100){init()}}};function rm(child){el.removeChild(child)}function init(){var bar=createBar(rm);el.appendChild(bar.el);applyGo=bar.go}addCss();addClass(el,"lwloadingbar");if(opts.id)el.id=opts.id;if(opts.classname)addClass(el,opts.classname);if(opts.target){el.style.position="relative";opts.target.insertBefore(el,opts.target.firstChild)}else{el.style.position="fixed";document.getElementsByTagName("body")[0].appendChild(el)}init();return lwloadingbar}if(typeof exports==="object"){module.exports=LWLoadingbar}else if(typeof define==="function"&&define.amd){define([],function(){return LWLoadingbar})}else{root.LWLoadingbar=LWLoadingbar}})(this);
    </script>

    <script type="text/javascript">
        var options = { id: 'lw-loading-bar' };var lwloadingbar = new LWLoadingbar( options );lwloadingbar.go(100);
    </script>

<?php

}
add_action('wp_footer', 'lw_loading_bar_inject_element');

global $lw_lb_enableonlogin;

if ($lw_lb_enableonlogin == 'true') {
    add_action( 'login_footer', 'lw_loading_bar_inject_element' );
}

?>