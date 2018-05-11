<?php
if( !defined( 'ABSPATH')){ exit(); }

function x19_info_for_wm($wm){
	$arr = array();
	$arr['err']=0;
	$arr['wmid'] = '';
	$result = get_curl_parser('https://passport.webmoney.ru/asp/CertView.asp?purse='.$wm, '', 'moduls', 'x19');
	if(!$result['err']){
		$out = $result['output'];
		if(strstr($out, 'Object moved')){
			$arr['err']=1;
		} else {
			$urlwmid = '';
			if(preg_match('/WebMoney.Events" href="(.*?)">/s',$out, $item)){
				$urlwmid = trim($item[1]);
			}
			$wmid = explode('?',$urlwmid);
			$wmid = trim(is_isset($wmid,1));
			if($wmid){
				$arr['wmid'] = $wmid;
			} else {
				$arr['err'] = 1;	
			}
		}
	} else {
		$arr['err']=1;
	}		
	
	return $arr;
}

function x19_phone($tel){
	$tel = str_replace(array('(',')','-',' '),'',$tel);
	if(strstr($tel,'+')){
		return mb_substr($tel,2,mb_strlen($tel));
	} else {
		return $tel;
	}
}