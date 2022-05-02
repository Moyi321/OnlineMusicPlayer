<?php
/**************************************************
 * MKOnlinePlayer 修复版
 *************************************************/

/************ ↓↓↓↓↓ 如果网易云音乐歌曲获取失效，请将你的 COOKIE 放到这儿 ↓↓↓↓↓ ***************/
$netease_cookie = '_ntes_nnid=9eb3c8241604169a95bc4dc49248f508,1651461399745; _ntes_nuid=9eb3c8241604169a95bc4dc49248f508; NMTID=00OOhAPouofpXpwuEZIldA2_9TvBSIAAAGAgsUkIQ; WNMCID=zunfei.1651461399867.01.0; WEVNSM=1.0.0; WM_NIKE=9ca17ae2e6ffcda170e2e6eeb3eb698cabbcd7e53dbceb8ba7c45a829f8aadd14a8e9fadd9c6469a93fcd3c42af0fea7c3b92a95f0b7bbd641b2bdf7b6e13fabecbfb5e552b5b9ada8db6d86a6ada8c16db69999abb8599896bcd8ed5db2b2aa84e652919a998df9349a95bd85db7fa5af9f8bd16782eda6d0e87fa2eae5b2d06087ee89ccbb5a81ad8fccf564a28da3bbce3cf58fa493e444b7e99ebacb5391a69daad8538abdb9d3c4599c86a492cc41f38982a7d837e2a3; WM_NI=W0yZWKrc9Ub5i4qTfZwgcGXgFzcGBgvGWJ5STJt7nWrnfcgZSFWlz8ZKsdXugqQjRmB3j%2Feub6%2BQMTE2G%2B8RCsA%2BvhbSmZO07QlraHu7RYHYSzSIUYJcw2ASXZlqIK1WNFU%3D; WM_TID=v%2BjEZmkKEJBABFFRVUKEQYUJtXMIMonB; _iuqxldmzr_=33; JSESSIONID-WYYY=g54ZxSEySZvvvemKs%5C%5C9JzyE0pRN22WtYZzs4znggnonDhBKycR9mz5VRu2n7ZngM4qJgPMyn8PqpsP3l3M4QEZ2pezQlMHVBJK8qcZeU6K%2FZI6EdF52hVcydi2dga6%5CbGYEwKtQXoPHBV4%5CIWqrQDCsJ60%2FTUWFoPrTwSiOtcqtfj3i%3A1651463216894; __snaker__id=wkDpCYM2GduzbPq7; _9755xjdesxxd_=32; gdxidpyhxdE=e71eCW5ZT1CH6Z4P1mi46lvXREWJTCYsb%5Cc4gUe4HjUVm3XD8bzLbz4c5BGDLOciyELSvYHGsyEiMPkThn1Kfd3O4dfAaJsatytqbyat%5CQW2GyDpO7wAYwo%5CKkZj%2FmKLf8gdN%5Ck%2FR26W%5CHd06VW82TN9u%2BGcevxkNUTYUrn%5CRP1t2ZHM%3A1651462324318; MUSIC_U=431befb5fda3c9f4fa7a9edbb414f173fab5b243b55592787a7c156b7b0b47f4993166e004087dd35e33250fb283b864f6166ce87d78b42cb1fd872f5b1dc02d5f515a06d1e7c33bd4dbf082a8813684; __remember_me=true; __csrf=9636f74c4a2d5bc0d6be7da9d7d9a9a2; ntes_kaola_ad=1';
/************ ↑↑↑↑↑ 如果网易云音乐歌曲获取失效，请将你的 COOKIE 放到这儿 ↑↑↑↑↑ ***************/
/**
* 源项目地址
* https://github.com/mengkunsoft/MKOnlineMusicPlayer
* 
* 
**/


define('HTTPS', false);    // 如果您的网站启用了https，请将此项置为“true”，如果你的网站未启用 https，建议将此项设置为“false”
define('DEBUG', false);      // 是否开启调试模式，正常使用时请将此项置为“false”

/*
 如果遇到程序不能正常运行，请开启调试模式，然后访问 http://你的网站/音乐播放器地址/api.php ，进入服务器运行环境检测。
 此外，开启调试模式后，程序将输出详细的运行错误信息，方便定位错误原因。
 
 因为调试模式下程序会输出服务器环境信息，为了您的服务器安全，正常使用时请务必关闭调试。
*/



/*****************************************************************************************************/
if(!defined('DEBUG') || DEBUG !== true) error_reporting(0); // 屏蔽服务器错误

require_once('plugns/Meting.php');

use Metowolf\Meting;

$source = getParam('source', 'netease');  // 歌曲源
$API = new Meting($source);

$API->format(true); // 启用格式化功能

if($source == 'kugou' || $source == 'baidu') {
    define('NO_HTTPS', true);        // 酷狗和百度音乐源暂不支持 https
} elseif(($source == 'netease') && $netease_cookie) {
    $API->cookie($netease_cookie);    // 解决网易云 Cookie 失效
}

switch(getParam('types'))   // 根据请求的 Api，执行相应操作
{
    case 'url':   // 获取歌曲链接
        $id = getParam('id');  // 歌曲ID
        
        $data = $API->url($id);
        
        echojson($data);
        break;
        
    case 'pic':   // 获取歌曲链接
        $id = getParam('id');  // 歌曲ID
        
        $data = $API->pic($id);
        
        echojson($data);
        break;
    
    case 'lyric':       // 获取歌词
        $id = getParam('id');  // 歌曲ID
        
        $data = $API->lyric($id);
        
        echojson($data);
        break;
        
    case 'download':    // 下载歌曲(弃用)
        $fileurl = getParam('url');  // 链接
        
        header('location:$fileurl');
        exit();
        break;
    
    case 'userlist':    // 获取用户歌单列表
        $uid = getParam('uid');  // 用户ID
        
        $url= 'http://music.163.com/api/user/playlist/?offset=0&limit=1001&uid='.$uid;
        $data = file_get_contents($url);
        
        echojson($data);
        break;
        
    case 'playlist':    // 获取歌单中的歌曲
        $id = getParam('id');  // 歌单ID
        
        $data = $API->format(false)->playlist($id);
        
        echojson($data);
        break;
     
    case 'search':  // 搜索歌曲
        $s = getParam('name');  // 歌名
        $limit = getParam('count', 20);  // 每页显示数量
        $pages = getParam('pages', 1);  // 页码
        
        $data = $API->search($s, [
            'page' => $pages, 
            'limit' => $limit
        ]);
        
        echojson($data);
        break;
        
    default:
        echo '<!doctype html><html><head><meta charset="utf-8"><title>信息</title><style>* {font-family: microsoft yahei}</style></head><body> <h2>MKOnlinePlayer</h2><h3>Github: https://github.com/mengkunsoft/MKOnlineMusicPlayer</h3><br>';
        if(!defined('DEBUG') || DEBUG !== true) {   // 非调试模式
            echo '<p>Api 调试模式已关闭</p>';
        } else {
            echo '<p><font color="red">您已开启 Api 调试功能，正常使用时请在 api.php 中关闭该选项！</font></p><br>';
            
            echo '<p>PHP 版本：'.phpversion().' （本程序要求 PHP 5.4+）</p><br>';
            
            echo '<p>服务器函数检查</p>';
            echo '<p>curl_exec: '.checkfunc('curl_exec',true).' （用于获取音乐数据）</p>';
            echo '<p>file_get_contents: '.checkfunc('file_get_contents',true).' （用于获取音乐数据）</p>';
            echo '<p>json_decode: '.checkfunc('json_decode',true).' （用于后台数据格式化）</p>';
            echo '<p>hex2bin: '.checkfunc('hex2bin',true).' （用于数据解析）</p>';
            echo '<p>openssl_encrypt: '.checkfunc('openssl_encrypt',true).' （用于数据解析）</p>';
        }
        
        echo '</body></html>';
}

/**
 * 检测服务器函数支持情况
 * @param $f 函数名
 * @param $m 是否为必须函数
 * @return 
 */
function checkfunc($f,$m = false) {
	if (function_exists($f)) {
		return '<font color="green">可用</font>';
	} else {
		if ($m == false) {
			return '<font color="black">不支持</font>';
		} else {
			return '<font color="red">不支持</font>';
		}
	}
}

/**
 * 获取GET或POST过来的参数
 * @param $key 键值
 * @param $default 默认值
 * @return 获取到的内容（没有则为默认值）
 */
function getParam($key, $default='')
{
    return trim($key && is_string($key) ? (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default)) : $default);
}

/**
 * 输出一个json或jsonp格式的内容
 * @param $data 数组内容
 */
function echojson($data)    //json和jsonp通用
{
    header('Content-type: application/json');
    $callback = getParam('callback');
    
    if(defined('HTTPS') && HTTPS === true && !defined('NO_HTTPS')) {    // 替换链接为 https
        $data = str_replace('http:\/\/', 'https:\/\/', $data);
        $data = str_replace('http://', 'https://', $data);
    }
    
    if($callback) //输出jsonp格式
    {
        die(htmlspecialchars($callback).'('.$data.')');
    } else {
        die($data);
    }
}
