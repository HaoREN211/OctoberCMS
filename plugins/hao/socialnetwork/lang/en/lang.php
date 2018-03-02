<?php

return [
    'twitter' => [
        "name"              => "Twitter",
        "group"             => '推特',
        "description"       => "推特用户资料",
        "social_network"    =>  "社交网络",
        "base64_encoded_token_credentials"  =>  "Base64 Token",
        "autre"             =>  "",
        'token' =>  [
            "name"          =>  "Token",
            'description'   =>  "管理推特token",
            "consumer_key"  =>  "Twiter 用户秘钥",
            "consumer_secret"   =>  "Twitter 秘钥密码",
            "access_token"  =>  "Access Token",
            "access_token_secret"  =>  "Access Token Secret",
            "type"          =>  "Token 类型",
            "created_at"    =>  "Token 创建时间",
            "updated_at"    =>  "Token 更新时间",
        ],
    ],

    'form' =>[
      'save' => '保存',
    ],

    'plugin' => [
      'name' => '社交网络',
      'menus' => [
          'meetics' => 'Meetic',
          "group" => "teste",
      ],
        'meetics'=>[
            'update'    => '编辑Meetic会员',
            'create'    => '创建Meetic会员',
        ],
    ],

    'backend' => [
        "button"   =>  [
                "save"  =>  "保存",
                "saving"    =>  "保存中。。。",
                "synchronization"   => "同步",
                "synchronizationing"   => "同步中。。。",
                "cancel"    =>  "离开",
        ],

        'meetic' => [
           'id' => 'ID',
            'nationality' => '国籍',
            'name_column' => '名字',
            'city' => '城市',
            'age' => '年纪',
            'url' => '主页',
            'height' => '身高',
            'weight' => '体重',
            'images' => '照片',
            'imperfection' => '缺点',
            'region' => '省份',
            'description' => '自我介绍',
            'favorites' => '收藏',
            'flashs' => '照片',
            'personnality' => [
                'relation_type' => 'Prêt à m\'engager dans une relation ? :',
                'romantic' => 'Romantique :',
                'temper' => 'Trait de caractère le plus marqué :',
                'marriage' => 'Pour moi le mariage c\'est :',
                'children_wish' => 'Je veux des enfants :',
                'nationality' => 'Ma nationalité :',
            ],

            'physique' => [
                'body_shape'        => 'silhouette :',
                'attraction'        => 'Le plus attrayant chez moi :',
                'living_style'      => 'Mon style :',
                'look'              => 'Physiquement, je suis :',
                'ethnicity'         => 'Mon origine :',
                'eyes'              => 'Mes yeux :',
                'hair_color'        => 'Mes cheveux :',
                'hair_style'        => 'Longueur de mes cheveux :',
            ],

            'lifeway' => [
                'marital_status'        => 'Mon statut marital :',
                'smoker'                => 'Je fume :',
                'has_children'          => 'J\'ai des enfants :',
                'live_with'             => 'Je vis :',
                'job'                   => 'Ma profession :',
                'religion'              => 'Ma religion :',
                'religion_behaviour'    => 'Mon niveau de pratique de la religion :',
                'food_habit'            => 'Je mange :',
                'pet'                   => 'Mes animaux de compagnie :',
                'language'              => 'Je parle :',
                'studies'               => 'Mon niveau d\'études :',
                'income'                => 'Mes revenus :'
            ],

            'interest' => [
                'music'                 => '音乐',
                'leisure'               => '闲暇爱好',
                'hobbies'               => '兴趣',
                'movie'                 => '电影',
                'sports'                => '运动',
            ],

            'tabs' => [
                'personnality'  => 'Ma personnalité',
                'user'          => '个人信息',
                'physique'      => 'Mon physique',
                'lifeway'       => 'Mon mode de vie',
                'interest'      => '爱好',
            ]
        ],
        'twitter' => [
            'user' =>[
                'name'          =>  '推特用户名',
                'screen_name'   =>  '推特注册名',
                'location'      => '地理位置',
                'location_description'  => "推特用户地理位置",
                'profile_image_url' => '用户头像',
                "id"                => "推特ID",
                "static"            => "用户统计数据",
                'static_description'  => "用户每天关注用户数目，被关注数目，帖子总数，喜欢帖子总数",
                "followers_count"   =>  "被关注",
                "friends_count"     =>  "关注",
                "listed_count"      =>  "列表",
                "favourites_count"  =>  "喜欢帖子",
                "statuses_count"    =>  "帖子",
                "observation_date"  =>  "观察时间",
                "user_description"  =>  "用户描述",
                "user_description_description"  =>  "用户自我描述"
            ],

            'tab' => [
                "basic" => "基本信息",
                "static"    =>  "统计信息",
                ],
            'color' =>  "blue",
            "size"  =>  "3",
        ],
    ],

    'list' => [
        'manage' => [
            "twitter_user"  =>  "管理推特用户",
            "twitter_token" =>  "管理推特 Twiter",
        ],

        'no_records' =>[
            "twitter_user" => '木有推特用户',
        ],

        'search_prompt' =>[
            "twitter_user" => '搜索推特用户',
        ],
    ],

    'field' => [
        'create'    => [
            'twitter_user'  =>  '新建推特用户',
            'twitter_token' =>  "新建推特 Token"
        ] ,

        'edit'      => [
            'twitter_user' => '编辑推特用户',
            "twitter_token" =>  "编辑推特 Token",
        ],

        'preview'   => [
            'twitter_user' => '预览推特用户',
        ],
    ],

    'permissions' => [
        'meetic'    => [
            'access_meetics'    => "Meetic 查看权限",
        ],

        'twitter'    => [
            'access_twitter'    => "推特查看权限",
            'access_twitter_token'  =>  '管理推特token',
        ]
    ],
];
