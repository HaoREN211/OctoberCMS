<?php

return [
    'flash' =>  [
        'error' =>  [
            'createnewuser' =>  '用户并不存在, 创建失败！',
        ],

        'success'   =>  [
            'createnewuser' =>  '创建成功！',
        ],
    ],

    'hao'   =>  [
        'link'  =>  [
            'facebook'  =>  'https://www.facebook.com/hao.ren.79656',
        ]
    ],

    'instagram' =>[
        'group' =>  'Instagram',
        'updatedSuccess'   =>  '更新成功',
        'updatedError'      =>  '更新出错',
        'backend'   =>  [
            'list'  =>  [
                'title' =>  '管理 Instagram 账号',
                'no_records'    =>  '没有 Instagram 账号',
            ],

            'form'  =>  [
                'title' =>  '编辑用户',
                'create'    =>  '新建用户',
                'update'    =>  '编辑用户',
                'preview'   =>  '预览用户',
                'confirming'    =>  '返回中',
            ],

            'full_name' =>  '用户名',
            'full_name_description' =>  '用户显示名字',
            'username'  =>  '注册名',
            'username_description'  => '用户注册名',
            'followed_by'   =>  '关注我的人',
            'followed_by_description'   =>  '关注我的账号人数',
            'follows'   =>  '我关注的人',
            'follows_description'   =>  '我关注的账号人数',

            'biography' =>  '用户自我介绍',
            'biography_description' => '用户自我介绍',
        ],
    ],


    'twitter' => [
        "name"              => "Twitter",
        "myAccount"         =>  "我的账号",
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
        "twitter"   =>  [
            "user"  =>  [
                "confirm"   =>   "确定",
                "confirming"    =>  "同步中。。。",
                "getFollower"   =>  "同步关注我的账号列表",
                "getFriend"     =>  "同步我关注的账号列表",
                "getTweet"      =>  "同步我的帖子",
            ],

            "token" =>  [
                "synchronization"   =>  "同步成功",
            ],
        ],

        'synchronization'   =>  [
            "twitter"   =>  [
                'follower'  =>  '被关注已同步成功',
                'friend'    =>  '关注已同步成功',
                'tweet'    =>  '帖子已同步成功',
            ]
        ]
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

        'instagram' =>[
            'user'  =>[
                'new'   => "新建 Instagram 用户",
                'list'  =>  'Instagram 用户资料',
            ],
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
                "follower"          =>  "关注我的用户",
                "follower_description"  =>  "关注我的用户列表",
                "friend"            =>  "我关注的用户",
                "tweet_static"      =>  "我的帖子总数",
                "tweets"            =>  "我的帖子",
                "friend_description"    =>  "我关注的用户列表",
                "followers_count"   =>  "被关注",
                "friends_count"     =>  "关注",
                "listed_count"      =>  "列表",
                "favourites_count"  =>  "喜欢帖子",
                "statuses_count"    =>  "帖子",
                "observation_date"  =>  "观察时间",
                "user_description"  =>  "用户描述",
                "user_description_description"  =>  "用户自我描述",
                "new"   =>  "新增推特用户",
                "new_description"   =>  "用户主页链接或者用户名:"
            ],

            'tab' => [
                "basic" => "基本信息",
                "static"    =>  "统计信息",
                "follower"  =>  "关注我的用户",
                "friend"    =>  "我关注的用户",
                "tweets"    =>  "我的帖子",
                ],
            'color' =>  "blue",
            "size"  =>  "3",
        ],
        'tweet' =>[
            'create_ad' =>  '创建时间',
            'text'      =>  '推特',
        ],
    ],

    'list' => [
        'manage' => [
            "twitter_user"  =>  "管理推特用户",
            "twitter_token" =>  "管理推特 Twiter",
        ],

        'no_records' =>[
            "twitter_user" => '木有推特用户',
            "twitter_follower"  =>  "木有关注我的人，请同步",
            "twitter_friend"    =>  "我木有关注任何人，请同步",
            'tweets'            =>  "木有推特，请同步",
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
        ],

        'instagram'=>[
            'access_instagram'  =>'Instagram 查看权限',
        ],
    ],
];
