<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\switch_theme;//Demo插件英文名，改成你的插件英文就行了
use cmf\lib\Plugin;

//Demo插件英文名，改成你的插件英文就行了
class SwitchThemePlugin extends Plugin
{

    public $info = [
        'name'        => 'SwitchTheme',
        'title'       => '前台模板切换',
        'description' => '前台模板切换',
        'status'      => 1,
        'author'      => 'yingbo',
        'version'     => '1.0.1',
        'demo_url'    => '',
        'author_url'  => ''
    ];

    public $hasAdmin = 0;//插件是否有后台管理界面

    // 插件安装
    public function install()
    {
        return true;//安装成功返回true，失败false
    }

    // 插件卸载
    public function uninstall()
    {
        return true;//卸载成功返回true，失败false
    }

    //实现的switch_theme钩子方法
    public function switchTheme($param)
    {
        $config = $this->getConfig();

        // 判断是否是手机端
        if (cmf_is_mobile()) {
            return empty($config['mobile_theme']) ? '' : $config['mobile_theme'];
        }

        return empty($config['default_theme']) ? '' : $config['default_theme'];
    }

}