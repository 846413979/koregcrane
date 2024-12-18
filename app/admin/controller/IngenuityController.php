<?php

namespace app\admin\controller;

use app\portal\model\PortalPostModel;
use cmf\controller\AdminBaseController;

/**
 * 匠心
 * **/

class IngenuityController extends AdminBaseController
{
    public function site()
    {
        $site_info = cmf_get_option('ingenuity_site');
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
        if(!empty($data['bg_image'])){
            $site_info['bg_image'] = $data['bg_image'];
        }
        if (!empty($data['engineering_name']) && !empty($data['engineering_num'])) {
            foreach ($data['engineering_name'] as $key => $value) {
                if (empty($data['engineering_num'][$key])){
                    $this->error('请输入工程数量');
                }
                $site_info['engineering'][] = ['engineering_name'=>$value, 'engineering_num'=>$data['engineering_num'][$key]];
            }
        }

        cmf_set_option('ingenuity_site', $site_info);

        $this->success('保存成功');

    }


    // 优质服务(单页面编辑)
    public function postList()
    {
        $id = 1;

        $portalPostModel = new PortalPostModel();
        $post            = $portalPostModel->where('id', $id)->find();

        $this->assign('post', $post);

        return $this->fetch();
    }

    public function excellent_servicePost()
    {
        $data = $this->request->param();

        $portalPostModel = new PortalPostModel();

        $portalPostModel->adminEditPage($data['post']);

        $this->success('保存成功');

    }

}