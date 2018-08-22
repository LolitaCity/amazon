<?php
/**
 * fantem物联官网公用工具
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-11-04
 */
namespace Component;

class Fantem{
    /*
     * 获取客户端ip
     * 
     * @return ip
     */
    public static function getIp(){        
        $ip =$_SERVER['REMOTE_ADDR'];
        if($_SERVER['REMOTE_ADDR'] && ($_SERVER['REMOTE_ADDR']=="::1" || $_SERVER['REMOTE_ADDR']=="127.0.0.1")){
            $ip ="113.102.163.199";
        }
        return $ip;
    }
    
    /*
     * 根据ip获取ip归属地区信息
     *
     * @param string $ip  输入的ip
     * 
     * @return #
     */
    public static function ip2Area($ip='113.102.163.199'){
        $ip2 = self::getIp();
        if ($ip2=='127.0.0.1' || $ip2=="::1" || substr($ip2,0,3)=='192') {
            $ip ='113.102.163.199'; //深圳
        }else{
            $ip =$ip2;
        }        
        $url    ="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
        $data   =file_get_contents($url);
        $obj    =json_decode($data);
        $code   =$obj->code;
        if ($code != 0) {
            //没有查到默认返回深圳地区的信息
            return array(
                'id' => '440300',
                'province' => '广东',
                'city' => '深圳',
                'area' => '华南',
            );
        }
        $obj2       =$obj->data;        //综合地址信息 
        $country    =$obj2->country;    //国家     
        $area       =$obj2->area;       //地区
        $province   =$obj2->region;     //省
        $city       =$obj2->city;       //城市 
        $county     =$obj2->county;     //区/县
        $isp        =$obj2->isp;        //运营商
        $ip         =$obj2->ip;

        $country_id =$obj2->country_id; //国家代码
        $area_id    =$obj2->area_id;    //地区代码
        $city_id    =$obj2->city_id;    //城市代码
        $province_id=$obj2->region_id;  //省份代码
        $isp_id     =$obj2->isp_id;     //营运商代码
        $province   =str_replace('省',"", $province);
        if(strpos("市",$province)){            
            $province   =str_replace('市',"", $province);
        }
        $city       =str_replace("市","", $city);
        if(empty($county)){
            $county     =str_replace("县","", $county);
            if(strpos("区",$city)){
                $county =str_replace("区","", $county);
            } 
        }             
        $areaInfo['id']         = $area_id;
        $areaInfo['country']    =$country;
        $areaInfo['area']       = $area;
        $areaInfo['province'] 	= $province;
        $areaInfo['city']       = $city;
        $areaInfo['county'] 	= $county;
        $areaInfo['ip'] 	= $ip;
        $areaInfo['isp'] 	= $isp;
        return $areaInfo;
    }
}

