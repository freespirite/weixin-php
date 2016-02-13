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
        'article' => array(
                'title'=> '素材管理',
                'flag' => 'fa-picture-o',
                'sub' => array(
                    'index' => array('title'=> '我的素材库'),
                ),
            ),
        'message' => array(
                'title'=> '消息管理',
                'flag' => 'fa-pencil-square-o',
                'sub' => array(
                    'index' => array('title'=> '粉丝消息'),
                    'autoreply' => array('title' => '自动回复'),
                    'massmsg' => array('title' => '消息群发'),
                    'msgtpl' => array('title' => '消息模板'),
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
        '1' => 3, //普通免费用户可以绑定微信号数量
        '2' => 4, //付费用户可以绑定微信号数量
    ),
    /*
     * freespirite
    AppID(应用ID)wx3152faa31d086ea4
    AppSecret(应用密钥)b7bfe46a0fdecee7368f8741d547170a
     */
);
