<?php

return array(
    
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
                    'index' => array('title'=> '公众号设置'),
                    'password' => array('title' => '登录密码修改'),
                ),
            ),
        'message' => array(
                'title'=> '自动回复',
                'flag' => 'fa-pencil-square-o',
                'sub' => array(
                    'index' => array('title'=> '关注回复'),
                    'automsg' => array('title' => '自动回复'),
                    'massmsg' => array('title' => '消息群发'),
                    'msgtpl' => array('title' => '消息模板'),
                ),
            ),
        'article' => array(
                'title'=> '素材管理',
                'flag' => 'fa-picture-o',
                'sub' => array(
                    'index' => array('title'=> '我的素材库'),
                    'add' => array('title'=> '素材添加'),
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
    AppID(应用ID)wx3152faa31d086ea4
    AppSecret(应用密钥)b7bfe46a0fdecee7368f8741d547170a
     */
);
