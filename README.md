# ali_mqClass
====  
##本工具类主要针对阿里队列服务MQ的操作，采用HTTP方式接入MQ
-------  
###使用方法：
####1 初始化队列类<br>
 $mq=new mqQueue();<br>
####2 发送队列内容<br>
$return=$mq->sendmsg('{code:200,"msg":"asda"}');<br>
var_dump(json_decode($return,true));<br>
####3 接收队列内容
$return=$mq->Responsemsg();<br>
var_dump(json_decode($return,true));<br>