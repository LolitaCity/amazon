<?php
include_once './sqlHelper.class.php';
include_once './simple_html_dom.class.php';
include_once './class.phpmailer.php'; 
include_once './class.smtp.php'; 
index();

/*
 * 爬虫执行主程序
 * 
 * @return #
 */
function index(){
    
    $db     =new SqlHelper();
    $url=array();
    $urlSql ="select id,url_content,status from urls where status=1";
    $urls=$db->exe_dql($urlSql);
    foreach($urls as $vo){
        $url[]	=$vo['url_content'];
    }
    foreach($url as $vo){
        $result =getContent($vo);
        $s      =getRusultToSimlpe_html_dom($result);
        send_mail('service@aeotec.com ','New Comment',"We have a new evaluation on the product '{$s['name']}', the customer gave '{$s['svg_star_level']}',  the URL is <a href='{$s['links']}'>{$s['links']}</a>");
        send_mail('cxu@aeon-labs.com','New Comment',"We have a new evaluation on the product '{$s['name']}', the customer gave '{$s['svg_star_level']}',  the URL is <a href='{$s['links']}'>{$s['links']}</a>");
        send_mail('mzhang@aeon-labs.com','New Comment',"We have a new evaluation on the product '{$s['name']}', the customer gave '{$s['svg_star_level']}',  the URL is <a href='{$s['links']}'>{$s['links']}</a>");

    }
}

/*
     * simple_html_dom抓取所需数据，并返回
     * 
     * @param   str $url    需要抓取的页面
     * 
     * @return array  $result[]返回所需的数组
     */
    function getRusultToSimlpe_html_dom(&$url){
        //include_once EXTEND_PATH.'simple_html_dom.php';
        $html   =new simple_html_dom();
        $db     =new SqlHelper();
        $html->load($url);
        $domName        =$html->find("span[id=productTitle]");              //查询产品名称的id标签
        $domStar        =$html->find("span[class=arp-rating-out-of-text]"); //查询产品平均星级的class属性
        $productName    =trim($domName[0]->plaintext);
        $sql            ="select * from productinfo where name='{$productName}'";
        $dbProductInfo  =$db->exe_dql($sql);       
         //查看数据库，看该产品是否为新产品，如果是，则加入数据库；
        $sign=0;
        if(empty($dbProductInfo) && $sign==0){
            $sign=1;
            //说明是新产品，加入数据库
            $svg_star_level             =$domStar?trim($domStar[0]->plaintext):0;
            //$html->clear();
            $insertSql  ="insert into productinfo VALUES('NULL','$productName',0,'$svg_star_level')";
            $result     =$db->exe_dml($insertSql);
            if(!$result){                
                //getRusultToSimlpe_html_dom($url);
                return FALSE;
            }            
        }
        if(empty($dbProductInfo)){
            if($db==FALSE){
                $db =new SqlHelper();
            }            
            $dbProductInfo  =$db->exe_dql($sql);
        }
        /*获取最新评论
            获取所有评论的时间换为时间戳，按从大到小排序，每次检索对比最大时间
            大于最大时间则是新评论，提取评论星级和评论链接 
        */ 
        $domTime    =$html->find("span[class=a-size-base a-color-secondary review-date]");        

        if($domTime==FALSE){
            //没有评价，则返回
            return FALSE;
        }        
        $commentTime=array();           //评价时间集合(一条或多条)  
        foreach($domTime as $vo){
            $commentTime[]  =strtotime(trim(str_replace("on"," ",$vo->plaintext)));
        }       
        //进行时间戳排序，如果最大值大于数据库中保存的值，则说明是新评论，提取评论星级和评论超链接，并将辞职替换数据库的最新评论时间
        rsort($commentTime);
        //判断评价是否为新评价(上次最新评价时间大于等于评价列表的时间，说明没有最新评价)
        if($dbProductInfo[0]['last_comment_time']>=$commentTime[0]){
            return FALSE;
        }
        $productStar        =trim($domStar[0]->plaintext)?trim($domStar[0]->plaintext):0;   //产品平均星级
        //最新评论时间
        //$new_content_time   =date("F j, Y",$commentTime[0]);
        //最新评论的字符串
        $new_comment_array  =array();
        foreach($domTime as $vos){
            //取出最新评价节点（评价表中时间大于最后一次评价时间的都是最新评价）
            if(strtotime(trim(str_replace("on"," ",$vos->plaintext)))>$dbProductInfo[0]['last_comment_time']){
                //父节点的兄弟节点就是最新评价节点(一条或者多条)
                $new_comment_array[]=$vos->parent->prev_sibling();
            }
        }
        //var_dump($new_comment_array);exit;
        //比较星级是否发生变化,发生变化，则更新星级          
        if(($dbProductInfo[0]['svg_star_level']!=$productStar)||($dbProductInfo[0]['svg_star_level']!=$commentTime[0])){
            $map_0['svg_star_level']    =$productStar;
            $map_0['id']                =$dbProductInfo[0]['id'];
            $map_0['last_comment_time'] =$commentTime[0];
            $updateSql                  ="update productinfo set svg_star_level='$productStar',last_comment_time='$commentTime[0]' WHERE id=".$dbProductInfo[0]['id'];
            $db->exe_dml($updateSql);
        }        
        //评论链接集合
        $commentLinks=array();
        //提取评论星级和评论链接
        foreach($new_comment_array as $vsop){
            $star_level     =$vsop->find("span[class=a-icon-alt]")[0]->plaintext;
            $comment_link   ="https://www.amazon.com".$vsop->find("a[class=a-size-base a-link-normal review-title a-color-base a-text-bold]")[0]->attr['href'];
            $insertSql_1    ="insert into commentlinks VALUES('NULL',".$dbProductInfo[0]['id'].",'$star_level','$comment_link','$commentTime[0]')";
            //if()
            $resultInsert   =$db->exe_dml($insertSql_1);
            if($resultInsert){
                $commentLinks[]         =$comment_link;
            }            
        }        
        $product['name']            =$productName;        
        $product['svg_star_level']  =$productStar;
        $product['links']           =$commentLinks;
        $html->clear(); 
        return $product;
    } 

/*
 * curl获取页面
 * 
 * @param str $url  需要抓取的页面url
 * 
 * @return str  $content 抓取到的页面
 */
function getContent($url){
    $ch =curl_init();
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);    // 跳过证书检查  
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);         // 从证书中检查SSL加密算法是否存在  
    curl_setopt($ch,CURLOPT_URL, $url);                //要访问的地址
    curl_setopt($ch,CURLOPT_HEADER,0);                 //不把头包含在输出中
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);        //执行结果是否被返回，不返回               
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    $content    =curl_exec($ch);
    curl_close($ch);
    //file_put_contents('./aas.html',$content);
    return $content;
}        

/*
 * 邮件发送程序
 * 
 * @param   str $to         收件人email
 * @param   str $fromname   发件人昵称
 * @param   str $title      邮件主题
 * @param   str $content    邮件内容
 * 
 * @return bool
 */

function send_mail($to,$fromname,$title,$content){
    try { 
        $mail = new PHPMailer(true); 
        $mail->IsSMTP(); 
        $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码 
        $mail->SMTPAuth = true; //开启认证 
        $mail->Port = 25; //端口请保持默认
        $mail->Host = "smtp.qq.com"; //使用QQ邮箱发送
        $mail->Username = "605333742@qq.com"; //这个可以替换成自己的邮箱
        $mail->Password = "bpdpwntzkhhtbfbh"; //注意 这里是写smtp的授权码 写的不是QQ密码，此授权码不可用
        //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示 
        $mail->AddReplyTo("605333742@qq.com","mckee");//回复地址 
        $mail->From = "605333742@qq.com"; 
        $mail->FromName = $fromname; 
        $to = $to; 
        $mail->AddAddress($to); 
        $mail->Subject = $title; 
        $mail->Body = $content;
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略 
        $mail->WordWrap = 80; // 设置每行字符串的长度 
        //$mail->AddAttachment("f:/test.png"); //可以添加附件 
        $mail->IsHTML(true); 
        $mail->Send(); 
            echo '邮件已发送';
        } catch (phpmailerException $e) { 
            echo "邮件发送失败：".$e->errorMessage(); 
        } 
    return true;
}
