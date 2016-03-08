<?php
namespace Org\Util;
class Sendmessage {
	public $apikey;
	public function __construct(){
		$this->apikey = '07e2595e275430b23b9454bf8887e7df';
	}
	/**
	* 智能匹配模版接口发短信
	* apikey 为云片分配的apikey
	* text 为短信内容
	* mobile 为接受短信的手机号
	*/
	function send_sms($text, $mobile){
	    $url="http://yunpian.com/v1/sms/send.json";
	    $encoded_text = urlencode("$text");
	    $mobile = urlencode("$mobile");
	    $post_string="apikey=$this->apikey&text=$encoded_text&mobile=$mobile";
	    return $this->sock_post($url, $post_string);
	}
	
	/**
	* 模板接口发短信
	* apikey 为云片分配的apikey
	* tpl_id 为模板id
	* tpl_value 为模板值
	* mobile 为接受短信的手机号
	*/
	function tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile){
	    $url="http://yunpian.com/v1/sms/tpl_send.json";
	    $encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
	    $mobile = urlencode("$mobile");
	    $post_string="apikey=$apikey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
	    return $this->sock_post($url, $post_string);
	}
	
	/**
	* url 为服务的url地址
	* query 为请求串
	*/
	function sock_post($url,$query){
	    $data = "";
	    $info=parse_url($url);
	    $fp=fsockopen($info["host"],80,$errno,$errstr,30);
	    if(!$fp){
	        return $data;
	    }
	    $head="POST ".$info['path']." HTTP/1.0\r\n";
	    $head.="Host: ".$info['host']."\r\n";
	    $head.="Referer: http://".$info['host'].$info['path']."\r\n";
	    $head.="Content-type: application/x-www-form-urlencoded\r\n";
	    $head.="Content-Length: ".strlen(trim($query))."\r\n";
	    $head.="\r\n";
	    $head.=trim($query);
	    $write=fputs($fp,$head);
	    $header = "";
	    while ($str = trim(fgets($fp,4096))) {
	        $header.=$str;
	    }
	    while (!feof($fp)) {
	        $data .= fgets($fp,4096);
	    }
	    return $data;
	}
}