<?php


namespace App\Services;


class FaceID
{
    const APP_ID = "28112966";
    const API_KEY = "gKImZ9KhLWSFHdCdQj850ngU";
    const SECRET_KEY = "yHUwpiz1hmvTvmMvv4jf0zj4vKqvLgPI";
    const Image_Type = "URL";  //设置图片类型，必须大写
    const OPTIONS = ['max_face_num'=>5,'face_field'=>'age,beauty,gender,expression'];   // 最大人脸数及返回 可选参数
    const GroupList = 'Hero';  //组
    private static $Client;

    public function __construct()
    {
        require_once "AipFace.php";  //百度云官方sdk，下载后拖拽到目录进行查找

        self::$Client = new \AipFace(self::APP_ID,self::API_KEY,self::SECRET_KEY);
    }

    //人脸识别
    public static function FaceCheck()
    {
        $image = "http://rkei10idw.hb-bkt.clouddn.com/255.png";

        $CheckRes = self::$Client->detect($image,self::Image_Type);

        if (empty($CheckRes['result'])) return error();

        return succeed();
    }

    //人脸搜索
    public static function FaceSearch()
    {
        $image = "http://rkei10idw.hb-bkt.clouddn.com/255.png";

        $SearchRes = self::$Client->search($image,self::Image_Type,self::GroupList);

        if (empty($SearchRes['result'])) return error();

        return succeed();
    }

    //人脸注册
    public static function FaceRegister()
    {
        $uid = 1;

        $image = "http://rkei10idw.hb-bkt.clouddn.com/255.png";

        $addRes = self::$Client->addUser($image,self::Image_Type,self::GroupList,$uid);

        if (empty($addRes['result'])) return error();

        return succeed();
    }

    //人脸更新
    public static function FaceUp()
    {
        $uid = 1;

        $image = "http://rkei10idw.hb-bkt.clouddn.com/255.png";

        $UpRes = self::$Client->updateUser($image,self::Image_Type,self::GroupList,$uid);

        if (empty($UpRes['result'])) return error();

        return succeed();
    }
}
