<?php

return array(
    'LOG_RECORD' => true, // 开启日志记录
    
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
    
    'LOG_TYPE'  =>  'File', // 日志记录类型 默认为文件方式
    
    'LAYOUT_ON' => TRUE,
    
    'LAYOUT_NAME' => 'common/layout',
    
    'MENU_ARRAY' => array(
        'index' => array(
                'title'=> '首页',
                'flag' => 'fa-tachometer',
            ),
        'account' => array(
                'title'=> '系统设置',
                'flag' => 'fa-desktop',
                'sub' => array(
                    'add' => array('title' => '新增公众号向导'),
                    'index' => array('title'=> '我的公众号列表'),
                ),
            ),
        'material' => array(
                'title'=> '素材管理',
                'flag' => 'fa-picture-o',
                'sub' => array(
                    'index' => array('title'=> '我的素材库'),
                    'add' => array('title'=> '素材添加'),
                ),
            ),
        'message' => array(
                'title'=> '信息发布',
                'flag' => 'fa-pencil-square-o',
                'sub' => array(
                    'index' => array('title'=> '关注回复设置'),
                    'automsg' => array('title' => '自动回复设置'),
                    'massmsg' => array('title' => '消息群发'),
                    'msgtpl' => array('title' => '消息模板'),
                ),
            ),
        'users' => array(
                'title'=> '用户管理',
                'flag' => 'fa-picture-o',
                'sub' => array(
                    'index' => array('title'=> '用户列表'),
                    'group' => array('title'=> '用户分组'),
                ),
            ),
        
        'report' => array(
                'title'=> '报表统计',
                'flag' => 'fa-bar-chart-o',
                'sub' => array(
                    'index' => array('title'=> '用户分析'),
                ),
            ),
    ),
    
    'USER_WX_LIMIT' => array(
        '1' => 2, //普通免费用户可以绑定微信号数量
        '2' => 10, //付费用户可以绑定微信号数量
    ),
    /*
     * freespirite
    AppID(应用ID)wx4152faa31d086ea4
    AppSecret(应用密钥)a7bfe46a0fdecee7368f8741d547170a
     */
);
