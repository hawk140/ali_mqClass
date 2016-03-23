<?php



//GBK to UTF-8
function utf8toutf($s) {
    if (is_array($s)) {
        $sn = array();
        foreach ($s as $k => $v) {
            $sn[gbktoutf($k)] = gbktoutf($v);
        }
        return $sn;
    } else {
        return iconv('utf-8', 'gbk//IGNORE', $s);
    }
}
/**

*/
class mqQueue{
     public $openurl='http://publictest-rest.ons.aliyun.com';
     public function __construct(){
        $this->AccessKey='AccessKey';
        $this->SecretKey='SecretKey';
        $this->Topic='Topic';
        $this->ProducerId='PID_ProducerId';
        $this->ConsumerId='CID-ConsumerId';
		$this->time=time()."000";
	 }	
	/**
     * 发送队列信息
     * @param type $post_Body
     * @return type
     */	
	 public function sendmsg($post_Body){
		$post_Body=utf8toutf($post_Body);
        $sign2=sprintf("%s\n%s\n%s\n%s", $this->Topic, $this->ProducerId, md5($post_Body), $this->time);//$this->Topic."\n".$this->ProducerId."\n".md5($post_Body)."\n".$this->time;
		$sign = base64_encode(hash_hmac('sha1', htmlentities($sign2),$this->SecretKey, true)); 
		$header_arr= array(
            "Content-Type: text/plain;charset=UTF-8",
            "AccessKey:" . $this->AccessKey, 
			"ProducerId:" . $this->ProducerId,
            "Signature:" . $sign
        );
        return  $return=$this->curl_post($this->openurl.'/message/',$msg,$header_arr);
     }
	 /**
     * 接收队列信息
     * @return type
     */
	public function Responsemsg(){
        $sign2=sprintf("%s\n%s\n%s", $this->Topic, $this->ConsumerId,$this->time);//$this->Topic."\n".$this->ProducerId."\n".md5($post_Body)."\n".$this->time;
		$sign = base64_encode(hash_hmac('sha1', htmlentities($sign2),$this->SecretKey, true)); 
		$header_arr= array(
            "Content-Type: text/plain;charset=UTF-8",
            "AccessKey:" . $this->AccessKey, 
			"ConsumerId:" . $this->ConsumerId,
            "Signature:" . $sign
        );
        return  $return=$this->curl_post($this->openurl.'/message/','',$header_arr);
     }
	/**
     * 删除队列
     * @param type $msgHandle
     * @return type
     */
	public function deleteMsg($msgHandle) {
        $sign2 = sprintf("%s\n%s\ns\n%s", $this->Topic, $this->ConsumerId, $msgHandle, $this->time); //$this->Topic."\n".$this->ProducerId."\n".md5($post_Body)."\n".$this->time;
        $sign = base64_encode(hash_hmac('sha1', htmlentities($sign2), $this->SecretKey, true));
        $header_arr = array(
            "Content-Type: text/plain;charset=UTF-8",
            "AccessKey:" . $this->AccessKey,
            "ConsumerId:" . $this->ConsumerId,
            "Signature:" . $sign
        );
        return $return = $this->curl_post($this->openurl . '/message/', '', $header_arr, $mothod = 'DELETE');
    }
	/**
     * 队列请求
     * @param string $url
     * @param type $post_Body
     * @param type $header_arr
     * @return type
     */
     private function curl_post($url, $post_Body="",$header_arr) {
        $cookie_file = "./";
        $post_str = '';
        $post_str = substr($post_str, 0, - 1);
        $curl = curl_init();
        $url.="?topic=".$this->Topic."&time=".$this->time."&tag=http&key=http";//echo $url;
		curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header_arr);
        if($post_Body){
			curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_Body);
		}
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1"); // 模拟用户使用的浏览器
		curl_setopt($curl, CURLOPT_HEADER, false); //获取header信息
        $result = curl_exec($curl);
		curl_close($curl);
        return $result;
    }
}