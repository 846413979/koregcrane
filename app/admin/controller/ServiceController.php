<?php

namespace app\admin\controller;

use app\admin\model\RouteModel;
use app\admin\model\ThemeModel;
use app\portal\model\PortalPostModel;
use cmf\controller\AdminBaseController;

class ServiceController extends AdminBaseController
{
    public function site()
    {
        $site_info = cmf_get_option('service_site');
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
        if(!empty($data['internationalization'])){
            $site_info['internationalization'] = $data['internationalization'];
        }
        if(!empty($data['internationalization_image'])){
            $site_info['internationalization_image'] = $data['internationalization_image'];
        }
        if (!empty($data['area']) && !empty($data['country']) && !empty($data['color'])) {
            foreach ($data['area'] as $key => $value) {
                if (empty($data['country'][$key])){
                    $this->error('请输入国家数量');
                }
                if (empty($data['color'][$key])){
                    $this->error('请输入颜色');
                }
                $site_info['areas'][] = ['area'=>$value, 'country'=>$data['country'][$key], 'color'=>$data['color'][$key]];
            }
        }
        if (!empty($data['company_urls']) && !empty($data['company_names'])) {
            foreach ($data['company_urls'] as $key => $value) {
                if (empty($data['company_names'][$key])){
                    $this->error('图片名称不能为空');
                }
                $site_info['companies'][] = ['url'=>$value,'name'=>$data['company_names'][$key]];
            }
        }

        cmf_set_option('service_site', $site_info);

        $this->success('保存成功');

    }


    // 优质服务
    public function excellent_service()
    {
        $site_info = cmf_get_option('excellent_service_site');
        $this->assign("site_info", $site_info);
        return $this->fetch();
    }

    public function serviceSitePost()
    {
        if (!$this->request->isPost()) {
            $this->error('请求错误');
        }
        $data = $this->request->post();

        cmf_set_option('excellent_service_site', $data);

        $this->success('保存成功');

    }

}