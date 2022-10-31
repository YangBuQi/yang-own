<?php


namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;
use function Symfony\Component\String\s;

//单例模式  特点：三私一公 【私有化静态属性，私有化构造方法，私有化克隆方法，公有化静态方法】
class Es
{
    //私有属性，存放实例
    private static $client;

    //私有化构造方法，防止外界创建实例
    public function __construct()
    {
        self::$client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
    }

    //私有化克隆方法，防止外界克隆
    public function __clone(){}

    /**
     * 公有化方法，获取实例
     * 创建索引,创建Ik
     */
    public static function CreateEs($index)
    {
        $params = [
            'index' => $index,  //索引名称
            'type' => '_doc',    //索引类型，默认为_doc
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'name' => [
                            'type' => 'text',  //相当于mysql中like索引
                            'analyzer' => 'ik_max_work',
                            'search_analyzer' => 'ik_max_work'
                        ]
                    ],
                ],
            ]
        ];

        $response = self::$client->indices()->create($params);

        return succeed();
    }

    //添加数据
    public static function AddEs($index,$id,$data)
    {
        $params = [
            'index' => $index,
            'type' => '_doc',
            'id' => $id,          //索引ID，不写会自动生成，方便后期删除设置为数据ID
            'body' => $data
        ];

        self::$client->index($params);

        return succeed();
    }

    //高亮搜索
    public static function SearchEs($index,$field,$word)
    {
        $records = [
            'index' => $index,
            'type' => '_doc',
            'body' => [
                'query'=> [
                    'match' => [
                        $field => $word
                    ]
                ],
                'highlight' => [
                    'fields' => [
                        $field => [   //filed为存储到es当中数据的字段名
                            'pre_tags' => ["<span style='color:#ff0000;'>"],
                            'post_tags' => ["</span>"],
                        ]
                    ]
                ]
            ]
        ];

        $result = self::$client->search($records)->asArray();

        foreach ($result['hits']['hits'] as $k=>$v)
        {
            $result['hits']['hits'][$k]['_source'][$field] = $v['highlight'][$field]['0'];
        }

        $info = array_column($result['hits']['hits'],'_source');

        return $info;
    }

    //删除索引中某一条数据 || 删除索引
    public static function DelEs($index,$id)
    {
        if (empty($id)) //如果ID不存在，那便是删除索引
        {
            $params = [
                'index' => $index
            ];

            self::$client->indices()->delete($params);
        }

        $params = [
          'index' => $index,
          'id' => $id   //id存在，则是删除索引中的单条数据
        ];

        self::$client->delete($params);

        return succeed();
    }
}
