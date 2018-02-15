<?php

return [
    'list'  => [
        'no_records' => [
            'vocabulary'    => '木有单词',
            'translation'   => '木有释义',
        ],
    ],

    'permission' => [
        'label' => [
            'all_vocabulary'        => '单词所有权限',
            'create_vocabulary'     => '新建单词',
            'manage_vocabulary'     => '修改单词',
            'delete_vocabulary'     => '删除单词',
            'create_translation'    => '新建释义',
            'manage_translation'    => '管理释义',
            'delete_translation'    => '删除释义',
            'guest_vocabulary'      => '游客登录',
        ],
    ],


    'form' =>[
        'vocabulary' => [
            'create' => [
                'creating' =>'单词创建完毕',
            ],
        ],

        'save' => '保存',
        'create' => [
            'vocabulary'  => '新建单词',
            'translation' => '新建释义',
        ],

        'close' => '取消',

        'create_close' => [
            'vocabulary' => '新建单词并保存',
        ],

        'save_close'    => '保存并倒退',

        'update' => [
            'vocabulary' => '编辑单词',
        ],

        'preview' => [
            'vocabulary' => '预览单词',
        ],

        'delete' =>[
            'vocabulary'  => '删除选中单词',
            'translation' => '删除选中释义',
        ],

        'confirm' => [
            'delete' =>[
                'vocabulary' => '确认删除单词？'
            ],
        ]
    ],

    'plugin' => [
      'name' => '字典',
      'menus' => [
          'vocabulary' => '单词',
          'translation' => '释义',
      ],

    ],

    'backend' => [
        'required' => '是必需的',

        'dictionary' => [
            'id'            => 'ID',
            'name'          => '单词',
            'language'      => '语言',
            'translation'   => '翻译',
        ],

        'translation' => [
            'name'              => '变位',
            'description'       => '释义',
            'grammaticalgender' => '阴阳性',
            'partofspeech'      => '词性',
            'singularandplural' => '单复数',
        ],
    ],
];
