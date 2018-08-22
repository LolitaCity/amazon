<?php
ini_set("magic_quotes_runtime",0);
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
    $url    =array();
    $urlSql ="select id,product_url,status from product_url_list where status=1";
    $urls=$db->exe_dql($urlSql);
    foreach($urls as $vo){
        $url[]	=$vo['product_url'];
    }
    $commentResult  =array(); 
    foreach($url as $vo){
        $result =getContent($vo);
        $s      =getRusultToSimlpe_html_dom($result);
        if(!empty($s)){
            $commentResult[]=$s;
        }
    }
    //var_dump($commentResult);
    if(!empty($commentResult)){
        foreach($commentResult as $vo){
            foreach($vo as $vos){
                //查询商家公司名称
                $reseller               ="select reseller_company_name,reseller_contact_name,reseller_contact_email from reseller where reseller_amazon_name='{$vos['reseller']}'  limit 1";
                $resellerInfo           =$db->exe_dql($reseller);
                $resellerInfo           =trim($resellerInfo[0]);
                $reseller_name          =trim($resellerInfo['reseller_company_name']);
                $reseller_amazon_name   =trim($vos['reseller']);
                $product_name           =trim($vos['product']);
                $warning_price          =trim($vos['currencySymbol']).trim($vos['price']);
                $minimum_price          =trim($vos['currencySymbol']).trim($vos['minimumPrice']);
                $insertSql              ="INSERT INTO warning(reseller_name,reseller_amazon_name,product_name,warning_price,minimum_price) VALUES('{$reseller_name}','{$reseller_amazon_name}','{$product_name}','{$warning_price}','{$minimum_price}')";
                if($db->exe_dml($insertSql)){
                    //数据库添加成功，给经销商发送邮件
                    $content    ="About {$product_name}, our company requires a minimum price of {$minimum_price}, but your price is {$warning_price}, less than the minimum price required by our company!";
                    send_mail($resellerInfo['reseller_contact_email'],'AEOTEC','Warning about the top selling price', $content);
                }
            } 
        }
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
    /*查询产品名称 start*/
    $domName     =$html->find("h1[class=a-size-large a-spacing-none]");              
    $productName =trim($domName[0]->plaintext);
    /*产品名称 end*/
    /*货币符号 start*/
    $price       =$html->find("span[class=a-size-large a-color-price olpOfferPrice a-text-bold]")[0]->plaintext;
    $currencySymbol ='';
    for($i=0;$i<strlen($price);$i++){
        if(!is_numeric($price[$i])){
            $currencySymbol.=trim($price[$i]);
        }else{
            break;
        }
    }
    /*货币符号 end*/
    /*根据产品名称和货币符号查询公司规定的最低价 start*/
    $miniPriceSQL   ="select minimum_price from minimum_price where product_name='{$productName}' and currency='{$currencySymbol}' and status=1";
    $miniPrice      =$db->exe_dql($miniPriceSQL)[0];
    $minimumPrice   =getPrice($miniPrice['minimum_price']);
    /*根据产品名称和货币符号查询公司规定的最低价 end*/
    /*获取所有卖家报价 start*/
    $resellerInfo   =$html->find("span[class=a-size-large a-color-price olpOfferPrice a-text-bold]");  
    $data           =array();    
    foreach ($resellerInfo as $vo){
        $resellerPrice_ =getPrice(trim(str_replace($currencySymbol,'',$vo->plaintext))); 
        //轮询产品价钱，小于规定价钱的，将商家信息存入临时表，发送警告信息，完了之后删除临时表数据
        if($resellerPrice_<$minimumPrice){
            $grandParent                =$vo->parent->parent;
            $product['reseller']        =$grandParent->find("div[class=a-column a-span2 olpSellerColumn]")[0]->find('a')[0]->plaintext?$grandParent->find("div[class=a-column a-span2 olpSellerColumn]")[0]->find('a')[0]->plaintext:"amazon";		//亚马逊商家
            $product['product']         =$productName;          					//违规售价的产品
            $product['price']           =$resellerPrice_;       					//违规的价钱
            $product['minimumPrice']    =$minimumPrice;         					//我公司规定的最低售价
            $product['currencySymbol']  =$currencySymbol;       					//货币符号
            $data[]                     =$product; 
        }
    }
    /*获取所有卖家报价 end*/
    $html->clear(); 
    return $data;
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
 * 将价钱中的逗号转换为点,将字符串转为整型或者浮点型
 * 
 * @param str  $price 用逗号隔开的商品价钱
 * 
 * @return str $price
 */
function getPrice($price){
    if(strpos($price,',')){
        $price  =(float)str_replace(',','.',$price);
    }else{
        $price  =(int)$price;
    }
    return $price;
}


/*
 * 邮件发送程序
 * 
 * @param   str $to         收件人email
 * @param   str $fromname   发件人昵称
 * @param   str $title      邮件主题
 * @param   str $content    邮件内容
 * 
 * @return #
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
