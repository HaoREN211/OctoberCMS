<?php

return [
    'plugin'    => [
        'cv' => [
            'name'  => '简历',
            'create' => '新建简历',
            'update'=>  '更新简历',
            'saving'=> '保存中...',
            'deleting'=> '删除中...',
            'create_and_close' => '新建简历并退出',
            'update_and_close' => '更新简历并退出',
            'delete_confirm'=> '确认删除此简历？',
            'cancel' => '取消',
            'index_page_name'   => '简历列表',
            'no_records' => '无简历',
            'search_prompt'=>'搜索'
        ],
    ],
    'models' => [
        'cv' => [
            'name'  => '简历名称',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'start_date' => '开始时间',
            'end_date'  => '结束时间',
            'school'    => '学校',
            'background'    => '学历',
            'domain'    => '专业',
            'user_id'   => '用户标识',
        ],
        'formation' =>  [
            'name'  => '教育经历',
            'no_records' => '无教育经历',
            'create'    => '新建教育经历',
            'delete'    => '删除所选教育经历'
        ],
        'experience'=>[
            'enterprise'=>'公司',
            'position'=>'职务',
            'name'=>'工作经验',
            'no_records'=>'无工作经验',
            'create'=>'创建工作经历',
            'delete'=>'删除所选工作经历'
        ]
    ]
];

?>