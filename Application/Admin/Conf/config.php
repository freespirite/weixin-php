<?php

return array(
    
    'LAYOUT_ON' => TRUE,
    
    'LAYOUT_NAME' => 'common/layout',
    
    'MENU_ARRAY' => array(
        'index' => array(
            'title'=> '首页',
            'url' => U('admin/index/index'),
            ),
        'account' => array(
                'title'=> '账号设置',
                'sub' => array(
                    'index' => array('title'=> '公众号设置', 'url' => U('admin/account/index'),)
                ),
            )
    ),
);
