<?php


namespace App\Services;


class CloudCheck
{
    public static function cloudCheck()
    {
        $appId = "";
        $apiKey = "";
        $secretKey = "";

        $client = new \Luffy\TextCensor\Core($appId, $apiKey, $secretKey);
        $res = $client->textCensorUserDefined("沈唁志博客：https://qq52o.me"); //待审核文本字符串
        var_dump($res);
    }
}
