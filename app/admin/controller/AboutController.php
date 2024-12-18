<?php

namespace app\admin\controller;

use app\portal\model\PortalPostModel;
use cmf\controller\AdminBaseController;

/**
 * 关于我们
 * **/

class AboutController extends AdminBaseController
{
    public function site()
    {
        $site_info = cmf_get_option('about_site');
        $this->assign("site_info", $site_info);
        return $this->fetch();
    }

    public function sitePost()
    {
        if (!$this->request->isPost()) {
            $this->error('请求错误');
        }
        $data = $this->request->post();
        $site_info = [];
        if(!empty($data['description'])){
            $site_info['description'] = $data['description'];
        }
        if(!empty($data['introduction'])){
            $site_info['introduction'] = $data['introduction'];
        }
        if(!empty($data['left_image'])){
            $site_info['left_image'] = $data['left_image'];
        }
        if(!empty($data['right_image'])){
            $site_info['right_image'] = $data['right_image'];
        }
        if(!empty($data['bg_image'])){
            $site_info['bg_image'] = $data['bg_image'];
        }
        if (!empty($data['engineering_name']) && !empty($data['engineering_num']) && !empty($data['engineering_image'])) {
            foreach ($data['engineering_name'] as $key => $value) {
                if (empty($data['engineering_num'][$key])){
                    $this->error('请输入工程数量');
                }
                if (empty($data['engineering_image'][$key])){
                    $this->error('请上传工程图标');
                }
                $site_info['engineering'][] = ['engineering_name'=>$value, 'engineering_num'=>$data['engineering_num'][$key], 'engineering_image'=>$data['engineering_image'][$key]];
            }
        }
        if (!empty($data['photo_urls']) && !empty($data['photo_names'])) {
            $site_info['corporate_images'] = [];
            foreach ($data['photo_urls'] as $key => $url) {
                $photoUrl = cmf_asset_relative_url($url);
                $site_info['corporate_images'][] = ["url" => $photoUrl, "name" => $data['photo_names'][$key]];
            }
        }

        cmf_set_option('about_site', $site_info);

        $this->success('保存成功');

    }

}