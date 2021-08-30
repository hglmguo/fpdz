<?php
include_once "wx/WXBizMsgCrypt.php";
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.urldecode($_SERVER['QUERY_STRING']);
$Url_data=Url_Get($url);
$corpId = "*************"; //企业dorpid
$token = "********"; //企业号后台定义的token
$encodingAesKey = "**************"; // Aes 加密串 
/****以上三个参数与企业号保持一致就可
*然后把文件和wx文件夹一起放到项目下 就可以了
*****/
$sVerifyMsgSig = $_GET["msg_signature"];
$sVerifyTimeStamp = $_GET["timestamp"];
$sVerifyNonce = $_GET["nonce"];
$sVerifyEchoStr = $Url_data["echostr"];
$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);

if ($errCode == 0) {
	file_put_contents('./logwechat.txt',"1".$_SERVER['QUERY_STRING']."||".$url."\r\n",FILE_APPEND);
	print($sEchoStr);
} else {
	file_put_contents('./logwechat.txt',"2".$_SERVER['QUERY_STRING']."||".$url."\r\n",FILE_APPEND);
	print($errCode);
}



function Url_Get($str){
    $data = array();
	$cs_str=explode('?',$str);
    $parameter = explode('&',end($cs_str));
    foreach($parameter as $val){
		if(count(explode("msg_signature",$val))>1){
			$data["msg_signature"] = str_replace("msg_signature=","",$val);
		}
		if(count(explode("timestamp",$val))>1){
			$data["timestamp"] = str_replace("timestamp=","",$val);
		}
		if(count(explode("nonce",$val))>1){
			$data["nonce"] = str_replace("nonce=","",$val);
		}
		if(count(explode("echostr",$val))>1){
			$data["echostr"] = str_replace("echostr=","",$val);
		}      
    }
    return $data;
}
?>