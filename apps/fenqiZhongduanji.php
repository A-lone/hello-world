<?php
define('TOKEN', 'douniwan');
if ($_GET['debug'] == 'alone') {
	error_reporting(E_ALL);
}
function weChatCheckSignature(){
	$signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
	$token = TOKEN;
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
}
$xml = file_get_contents('php://input');
$data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA );
$returnData = array(
	'ToUserName' => $data['FromUserName'],
	'FromUserName' => $data['ToUserName'], 
	'CreateTime' => time(),
	'Content' => '稍安勿躁，还没开发好呢:）',
); 
$xml = "<xml>
<ToUserName><![CDATA[{$returnData['ToUserName']}]]></ToUserName>
<FromUserName><![CDATA[{$returnData['FromUserName']}]]></FromUserName>
<CreateTime>{$returnData['CreateTime']}</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[{$returnData['Content']}]]></Content>
</xml>";
$ret = $xml;
echo $ret;
@error_log(json_encode($_GET) . "\t" . json_encode($_POST) . "\t" . json_encode($data) . "\t" . $ret ."\n", 3, '/tmp/weChatLog.20140320.log');
