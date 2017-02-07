<?php
/*
 * filename:function.php
 * action:provide publif funtion
 * author:chengwenle
 * time:2017/01/23
 * lasttime:2017/01/23
 *
 */
 /*********************************************循环读取目录及其文件**************************************************************/ 
function file_list($path){  
    if ($handle = opendir($path))//打开路径成功  
    {  
        while (false !== ($file = readdir($handle)))//循环读取目录中的文件名并赋值给$file  
        {  
            if ($file != "." && $file != "..")//排除当前路径和前一路径  
            {  
                if (is_dir($path."/".$file))  
                {  
                    //echo $path.": ".$file."<br>";//去掉此行显示的是所有的非目录文件  
                    file_list($path."/".$file);  
                }  
                else  
                {  
					//include_once	"$path/$file";		此处加载文件
                   // echo $path."/".$file."<br>";  
                }  
            }  
        }  
    }  
}
//file_list('E:/wamp/www/test');

/*******************循环创建文件及文件夹********************/ 
function mkdirs($path){
	/*
		  
	*/
	file_put_contents($path.'index.lock','this dir is aleardy mkdir');
	for($i=1;$i<=10;$i++){	
		$file=$path.date('YmdHis').$i;
		
		if(!file_exists($file)){
			mkdir($file);
		}
		for($j=1;$j<=10;$j++){	
			$con=file_get_contents('./function.php');
			//file_put_contents($path.'/'.$j.'.html',$con);
			file_put_contents($file.'/'.$j.'.php',$con);
		}
	}
}

/*******************随机读取国内ip********************/ 
function rand_ip(){
	$ip_long = array(
		array('607649792', '608174079'), //36.56.0.0-36.63.255.255
		array('975044608', '977272831'), //58.30.0.0-58.63.255.255
		array('999751680', '999784447'), //59.151.0.0-59.151.127.255
		array('1019346944', '1019478015'), //60.194.0.0-60.195.255.255
		array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
		array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
		array('1947009024', '1947074559'), //116.13.0.0-116.13.255.255
		array('1987051520', '1988034559'), //118.112.0.0-118.126.255.255
		array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
		array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
		array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
		array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
		array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
		array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
		array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
	);
	$rand_key = mt_rand(0, 14);
	$huoduan_ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
	return $huoduan_ip;
}

/*******************pdo链接数据库********************/ 
function pdo($user,$pwd,$dbname,$host='localhost'){
	
	$dsn  =  'mysql:dbname='.$dbname.';host='.$host ;
	$user  =  $user ;
	$password  =  $pwd ;

	try {
		 $dbh  = new  PDO ( $dsn ,  $user ,  $password );
		 return $dbh;
	} catch ( PDOException $e ) {
		 echo  'Connection failed: '  .  $e -> getMessage ();
}
}

/*********************************************人民币数字转中文**************************************************************/  
//
function cny($ns) {
	static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"), 
	$cnyunits = array("圆","角","分"), 
	$grees = array("拾","佰","仟","万","拾","佰","仟","亿"); 
	list($ns1,$ns2) = explode(".",$ns,2); 
	$ns2 = array_filter(array($ns2[1],$ns2[0])); 
	$ret = array_merge($ns2,array(implode("", _cny_map_unit(str_split($ns1), $grees)), "")); 
	$ret = implode("",array_reverse(_cny_map_unit($ret,$cnyunits))); 
	return str_replace(array_keys($cnums), $cnums,$ret); 
}
function _cny_map_unit($list,$units) { 
	$ul = count($units); 
	$xs = array(); 
	foreach (array_reverse($list) as $x){ 
		$l = count($xs); 
		if($x!="0" || !($l%4)){
			$n=($x=='0'?'':$x).($units[($l-1)%$ul]); 
		}
		else{
			$n=is_numeric($xs[0][0]) ? $x : ''; 
		}
		array_unshift($xs, $n); 
	} 
	return $xs; 
}
//$value='23058.04';
//print cny($value);


/************易分析秘钥解密******************/  
function display_file_code($string = '', $skey = 'phpstat_license_file') {
        $strArr = str_split(str_replace(array('O01O0O', 'o0200o', 'oo030o'), array('=', '+', '/'), $string), 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return base64_decode(join('', $strArr));
}


/************易分析秘钥加密******************/  
function fetch_file_code($string = '', $skey = 'phpstat_license_file') {
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key].=$value;
        return str_replace(array('=', '+', '/'), array('O01O0O', 'o0200o', 'oo030o'), join('', $strArr));
}
//echo fetch_file_code('218.17.83.66:8000');
//echo display_file_code('MpjhEp4sLtjaEt3_LljigczeLnjsYe2_OfjiglweMDAO01O0O');


/************按时间顺序输出文件夹中的文件******************/  
function dir_size($dir,$url) {  
	$dh = @opendir ( $dir ); // 打开目录，返回一个目录流  
	$return = array ();  
	$i = 0;  
	while ( $file = @readdir ( $dh ) ) { // 循环读取目录下的文件  
		if ($file != '.' and $file != '..') {  
			$path = $dir . '/' . $file; // 设置目录，用于含有子目录的情况  
			if (is_dir ( $path )) {  
				echo 1;
			} elseif (is_file ( $path )) {   
				$filetime [] = date ( "Y-m-d H:i:s", filemtime ( $path ) ); // 获取文件最近修改日期   
			    $return [] = $url . '/' . $file;  
			}  
		}  
	}
	@closedir ( $dh ); // 关闭目录流   
	array_multisort($filetime,SORT_DESC,SORT_STRING, $return);//按时间排序  
	return $return; // 返回文件  
}  
//define('path',"E:/wamp/www/test/");   
//define('dirRoot',"../../..common/upload_thumb");   
//$thumbsNames=dir_size(path,dirRoot);
//var_dump($thumbsNames);   


/************按时间顺序输出文件夹中的文件******************/  