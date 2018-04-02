<?php

return [
    'plugin'    =>  [
        'name'  =>  '照片',
    ],

    'permission'    =>  [
        'photo' =>  [
            'tab'   =>  '查看照片的权力',
            'label' =>  '查看照片的权力',
        ],

        'photo_create'  =>  [
            'tab'   =>  '新建照片的权力',
            'label' =>  '新建照片的权力',
        ],

        'photo_manage'  =>  [
            'tab'   =>  '管理照片的权力',
            'label' =>  '管理照片的权力',
        ],
    ],

    'menu'  =>  [
        'photo' =>  [
            'create'    =>  '新建照片',
            'manage'    =>  '管理照片',
        ],
    ],

    'backend'   =>  [
        'photo' =>  [
            'url'   =>  '图片地址',
            'url_comment'   =>  '图片的完整url地址',
            'is_watched'    =>  '观看',
            'is_liked'    =>  '喜欢',
            'create_at'   =>  '创建时间',
            'updated_at'  =>  '更新时间',
        ],
    ],

    'form'  =>  [
        'photo' =>  [
            'create'    =>  '新建照片',
            'created'   =>  '照片创建成功',
            'update'    =>  '更新照片',
            'manage'    =>  '管理照片',
            'delete'    =>  '删除照片',
            'preview'   =>  '预览照片',
            'create_quite'  =>  '保存并离开',
            'creating'  =>  '新建中。。。',
            'updating'  =>  '更新中。。。',
            'update_quite'  =>  '更新并离开',
            'changing'  =>  '更换中。。。',
            'change'    =>  '更换照片',
            'view_origine'  =>  '查看原图',
        ],
        'cancel'    =>  '取消',
        'canceling' =>  '取消中。。。',
    ],
];
