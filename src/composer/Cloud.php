<?php


namespace App\Services;

use lishuo\oss\Manager;
use lishuo\oss\storage\StorageConfig;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

class Cloud
{
    public static function Cloud()
    {
        // 参数1：string $appId
        // 参数2：string $appKey
        // 参数3：string $region 地域名、比如（1）阿里云上海为例（http://oss-cn-shanghai.aliyuncs.com）（2）腾讯云上海为例（sh）直接地域名首字母即可（3）其它Region请按实际情况填写。

        $config = new StorageConfig("控制台查看获取", "控制台查看获取", "七牛云不需要配置这个参数，留空字符串");

        $storage = Manager::storage("云存储厂商") // 阿里云：aliyun、腾讯云：tencent、七牛云：qiniu
        ->init($config) // 初始化配置
        ->bucket("存储桶名称"); // 指定操作的存储桶

        // 查看文件列表
        $storage->get(10); // 指定查看10条
        // 上传文件
        $path = "./test.jpg";
        $result = $storage->put("test.jpg", $path);
        // 删除文件
        $keys = ['test.jpg'];
        $result = $storage->delete($keys);
    }

    public static function Qn()
    {
        $uploadMgr = new UploadManager();
        $auth = new Auth('','');  //ak,sk,到官网获取秘钥
        $token = $auth->uploadToken('');  //桶名
        list($ret, $error) = $uploadMgr->putFile($token, '', '');  //key为文件名称，path为上传文件路径
    }
}
