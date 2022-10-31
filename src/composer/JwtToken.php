<?php


namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken
{
    protected static $key = 'Jwt_Key';

    //设置Token
    public static function EnCode($data)
    {
        $payload = [
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000,
            'data' => $data
        ];

        $jwt = JWT::encode($payload, self::$key, 'HS256');

        return $jwt;
    }

    //解析Token
    public static function DeCode($jwt)
    {
        $decoded = JWT::decode($jwt, new Key(self::$key, 'HS256'));

        return $decoded->data;
    }
    
    //无限极分类（递归）
    public static function Tree($data , $pid = 0)
    {
        $arr=[];

        foreach ($data as $k=>$v)
        {
            if($v['pid'] == $pid)
            {
                $arr[$k]=$v;
                $arr[$k]['son']=self::Tree($data,$v['id']);
            }
        }

        return $arr;
    }
}
