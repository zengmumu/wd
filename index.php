<?php


/*
    方倍工作室
    http://www.cnblogs.com/txw1958/
    CopyRight 2014 All Rights Reserved
*/
//echo "<html><head></head><body><h1>goodbody</h1></body></html>";
define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
			header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    //响应消息
    public function responseMsg()
    {  
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
             
            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收事件消息
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
//              $content = "欢迎关注前端面试 ";
//              $content .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
				$content = array();
                $content[] = array("Title"=>"前端面试集锦", "Description"=>"", "PicUrl"=>"http://a1.jikexueyuan.com/home/201507/21/0e3c/55adaba4911b6.jpg", "Url" =>"http://www.qingkt.com/a/interview/");
                $content[] = array("Title"=>"js面试题", "Description"=>"", "PicUrl"=>"http://a1.jikexueyuan.com/home/201506/03/d166/556e584d46c15.jpg", "Url" =>"http://www.qingkt.com/a/interview/js/");
                $content[] = array("Title"=>"angular面试题", "Description"=>"", "PicUrl"=>"http://evgetgif.qiniudn.com/featured1.jpg", "Url" =>"http://www.qingkt.com/a/interview/ng/");
                $content[] = array("Title"=>"html5面试题", "Description"=>"", "PicUrl"=>"http://s15.sinaimg.cn/large/00486BHSgy6NfgRj3Maae", "Url" =>"http://www.qingkt.com/a/interview/htmlcss/");
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "SCAN":
                $content = "扫描场景 ".$object->EventKey;
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "COMPANY":
						$content = array();
                        $content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                        break;
                    default:
                        $content = "点击菜单：".$object->EventKey;
                        break;
                }
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "MASSSENDJOBFINISH":
                $content = "消息ID：".$object->MsgID."，结果：".$object->Status."，粉丝数：".$object->TotalCount."，过滤：".$object->FilterCount."，发送成功：".$object->SentCount."，发送失败：".$object->ErrorCount;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }
        if(is_array($content)){
            if (isset($content[0])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }

        return $result;
    }

    //接收文本消息
    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        //多客服人工回复模式
        if (strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗")){
            $result = $this->transmitService($object);
        }
        //自动回复模式
        else{
<<<<<<< .mine
            if (strstr($keyword, "文本")){
                $content = "这是个文本消息-我爱微信阿斯蒂芬";
=======
            if (strstr($keyword, "曾庆林")){
                $content = "一个自以为是的教书匠";
>>>>>>> .r66
            }else if (strstr($keyword, "面试")){
                 $content = array();
                $content[] = array("Title"=>"前端面试集锦", "Description"=>"", "PicUrl"=>"http://a1.jikexueyuan.com/home/201507/21/0e3c/55adaba4911b6.jpg", "Url" =>"http://www.qingkt.com/a/interview/");
                $content[] = array("Title"=>"js面试题", "Description"=>"", "PicUrl"=>"http://a1.jikexueyuan.com/home/201506/03/d166/556e584d46c15.jpg", "Url" =>"http://www.qingkt.com/a/interview/js/");
                $content[] = array("Title"=>"angular面试题", "Description"=>"", "PicUrl"=>"http://evgetgif.qiniudn.com/featured1.jpg", "Url" =>"http://www.qingkt.com/a/interview/ng/");
                 $content[] = array("Title"=>"html5面试题", "Description"=>"", "PicUrl"=>"http://s15.sinaimg.cn/large/00486BHSgy6NfgRj3Maae", "Url" =>"http://www.qingkt.com/a/interview/htmlcss/");
            }else if (strstr($keyword, "单图文")){
                $content = array();
                $content[] = array("Title"=>"单图文标题",  "Description"=>"单图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
                $content = array();
                $content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title"=>"多图文2标题", "Description"=>"", "PicUrl"=>"http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title"=>"多图文3标题", "Description"=>"", "PicUrl"=>"http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
            }else if (strstr($keyword, "音乐")){
                $content = array();
                $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3");
            }else{
<<<<<<< .mine
                $content = $this->searchKey($keyword);
=======
                //$content = date("Y-m-d H:i:s",time())."\n".$object->FromUserName."\n技术支持 前端面试";
               
//              $content = implode(',',$this->searchKey($keyword));
				$arr=$this->searchKey($keyword);
//				$content = array();
//              $content[]=$arr[0];
//              $content[]=$arr[1];
//              $content[]=$arr[2];
//              $content[]=$arr[3];
//              $content[]=$arr[4];
//              $content[]=$arr[5];
//              $content[]=$arr[6];
//              $content[]=$arr[7];
				$content=$arr;
    
>>>>>>> .r66
            }
            
            if(is_array($content)){
                if (isset($content[0]['PicUrl'])){
                    $result = $this->transmitNews($object, $content);
                }else if (isset($content['MusicUrl'])){
                    $result = $this->transmitMusic($object, $content);
                }
            }else{
                $result = $this->transmitText($object, $content);
            }
        }

        return $result;
    }

    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }

    //接收视频消息
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //日志记录
    private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 10000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }
<<<<<<< .mine
    private function searchKey($key){
    	define(DB_HOST, '101.200.183.111');  
		define(DB_USER, 'admin');  
		define(DB_PASS, 'admin');  
		define(DB_DATABASENAME, 'qktcms');  
		define(DB_TABLENAME, 'qkt_archives');  
		//数据库表的列名  
		
		//mysql_connect  
		$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());  
		mysql_select_db(DB_DATABASENAME, $conn); 
		$sql ="SELECT a.id,a.title,b.aid FROM qkt_archives AS a, qkt_addonarticle21  AS b WHERE a.title LIKE '%$key%' or b.explans LIKE '%$key%'AND a.id=b.aid LIMIT 0 , 20"; 
		$result = mysql_query($sql, $conn); 
		$content = array();             
		
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC))//与$row=mysql_fetch_assoc($result)等价  
		{  
			  $content[] = array("Title"=>"$row[title]", "Description"=>"", "PicUrl"=>"", "Url" =>"http://www.qingkt.com/plus/view.php?aid=$row[aid]");
			
		    
		}  
		//print_r($content);
		mysql_free_result($result);  
		mysql_close($conn);  
		return $content
    	
    }
=======
    
     private function searchKey($key)
     {
    	define(DB_HOST, '101.200.183.111');  
		define(DB_USER, 'admin');  
		define(DB_PASS, 'admin');  
		define(DB_DATABASENAME, 'qktcms');  
		define(DB_TABLENAME, 'qkt_archives');  
		//数据库表的列名  
		$num=rand(1,10);
		//mysql_connect  
		$conn =@mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());  
		mysql_select_db(DB_DATABASENAME, $conn); 
		mysql_query("SET NAMES UTF8"); 
		$sql ="SELECT a.id,a.title,b.aid FROM qkt_archives AS a, qkt_addonarticle21  AS b WHERE a.title LIKE '%$key%' or b.explans LIKE '%$key%'AND a.id=b.aid  LIMIT $num,5"; 
		$result = mysql_query($sql, $conn); 
		$con = array();             
		
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC))//与$row=mysql_fetch_assoc($result)等价  
		{  
			  $con[] = array("Title"=>"$row[title]", "Description"=>"", "PicUrl"=>"", "Url" =>"http://www.qingkt.com/plus/view.php?aid=$row[id]");
			
		    
		}  
		//print_r($content);
		mysql_free_result($result);  
		mysql_close($conn); 
		 
		return $con;
    	
    }
>>>>>>> .r66
}

?>