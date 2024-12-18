<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use app\admin\model\MessageModel;
use app\admin\model\SlideItemModel;
use app\admin\model\UserAccessLogModel;
use app\portal\model\PortalCategoryModel;
use app\portal\model\PortalPostModel;
use app\portal\model\ProductCategoryModel;
use app\portal\model\ProductModel;
use app\portal\model\ProfessionModel;
use app\portal\service\RedisService;
use app\portal\validate\InquiryValidate;
use cmf\controller\HomeBaseController;
use think\facade\Db;

class IndexController extends HomeBaseController
{

    private $isMobile;

    public function initialize()
    {
        // 产品分类
        $productCategoryModel = new ProductCategoryModel();
        $category_list = $productCategoryModel->order('list_order asc,create_time desc')->select();
        $this->assign('category_list', $category_list);

        $site_info = cmf_get_site_info();
        $this->assign('site_info', $site_info);
        parent::initialize();


        // 保存访问记录到redis
        $ip = cmf_client_ip();
        $url = $this->request->url();

        $redisService = new RedisService();
        $param = $this->request->param();
        $redisService::setUrl($ip,$url,$param);

        $this->isMobile = cmf_is_mobile();

    }

    public function index()
    {

        // 首页轮播图
        $slide_id = 1;
        $SlideItemModel = new SlideItemModel();
        $slides = $SlideItemModel->field('title,image')->where('slide_id', $slide_id)->order('list_order asc')->select();
        $this->assign('slides', $slides);


        // 首页推荐产品
        $productModel = new ProductModel();
        $hot_products = $productModel->where('is_recommended', 1)->order('list_order asc')->limit(20)->select();
        $this->assign('hot_products', $hot_products);

        // 首页设置
        $index_site = cmf_get_option('index_setting');
        $this->assign('index_site', $index_site);
        // 创新智造设置
        $innovate_site = cmf_get_option('innovate_site');
        $ingenuity_site = cmf_get_option('ingenuity_site');
        $quality_site = cmf_get_option('quality_site');
        $create = [
            'innovate_desc'=>$innovate_site['description'],
            'ingenuity_desc'=>$ingenuity_site['description'],
            'quality_desc'=>$quality_site['description'],
        ];
        $this->assign($create);

        return $this->fetch(':index');
    }

    public function product()
    {
        if ($this->request->isAjax()) {
            $category_id = $this->request->param('id', 0, 'intval');//分类id
            $weight = $this->request->param('weight', '', 'strval');//吨位
            $profession_ids = $this->request->param('profession_ids', '', 'strval');//行业标签

            $productModel = new ProductModel();
            $where = [];
            $whereRaw = '';
            if (!empty($category_id)) {
                $where[] = ['category_id', '=', $category_id];
            }
            if (!empty($weight)) {
                $whereRaw = 'JSON_CONTAINS(lifting_capacity, \'"' . $weight . '"\')';
            }
            if (!empty($profession_ids)) {
                $where[] = ['f.tag_id', 'IN', $profession_ids];
            }
            $page = 9;
            if($this->isMobile){
                $page = 12;
            }
            $list = $productModel->alias('p')->join('cmf_product_profession f', 'p.id=f.product_id', 'left')->field('p.id,title,thumbnail')->where($where)->where($whereRaw)->order('list_order asc,p.create_time desc')->group('p.id')->paginate(['list_rows' => $page, 'query' => $this->request->param()])->each(function ($item, $key) {
                $item['thumbnail'] = cmf_get_image_url($item['thumbnail']);
                $item['url'] = cmf_url('portal/index/product_info', array('id' => $item['id']));
            });

            $this->success('success', '', ['list' => $list, 'page' => $list->render()]);
        }


        $id = $this->request->param('id', 0, 'intval');

        $where = [];
        // id为空 全部分类产品
        if (empty($id)) {
            $category = ['id' => 0, 'name' => 'All Products'];
        } else {
            $productCategoryModel = new ProductCategoryModel();
            $category = $productCategoryModel->where('id', $id)->find();
            if (empty($category)) {
                $this->error('category not exits');
            }
            $where[] = ['category_id', '=', $id];
        }

        $this->assign('category', $category);

        $weight = $this->request->param('weight', '', 'strval');//吨位
        $profession_ids = $this->request->param('profession_ids', '', 'strval');//行业标签
        $whereRaw = '';
        if (!empty($category_id)) {
            $where[] = ['category_id', '=', $category_id];
        }
        if (!empty($weight)) {
            $whereRaw = 'JSON_CONTAINS(lifting_capacity, \'"' . $weight . '"\')';
        }
        if (!empty($profession_ids)) {
            $where[] = ['f.tag_id', 'IN', $profession_ids];
        }
        $productModel = new ProductModel();
        $page = 9;
        if($this->isMobile){
            $page = 12;
        }
        $list = $productModel->alias('p')->join('cmf_product_profession f', 'p.id=f.product_id', 'left')->field('p.id,title,thumbnail')->where($where)->where($whereRaw)->order('list_order asc,p.create_time desc')->group('p.id')->paginate(['list_rows' => $page, 'query' => $this->request->param()]);

        $this->assign('list', $list);
        $this->assign('page', $list->render());

        // 行业标签
        $professionModel = new ProfessionModel();
        $profession_list = $professionModel->field('id,name')->where('status', 1)->select();
        foreach ($profession_list as $profession) {
            $profession_ids_arr = explode(',', $profession_ids);
            $profession['checked'] = false;
            if(in_array($profession['id'], $profession_ids_arr)){
                $profession['checked'] = true;
            }
        }
        $this->assign('profession_list', $profession_list);

        $this->getBanner(2);

        // 产品设置
        $product_setting = cmf_get_option('product_setting');
        $this->assign('product_setting', $product_setting);


        return $this->fetch(':product');
    }

    // 详情页banner图
    public function getBanner($slide_id,$offset = 0)
    {
        // 产品banner
        $SlideItemModel = new SlideItemModel();
        $banner = $SlideItemModel->field('title,description,image')->where('slide_id', $slide_id)->order('list_order asc')->limit($offset,1)->select();

        $this->assign('banner', $banner[0]);
    }

    public function product_info()
    {
        $productModel = new ProductModel();
        $id = $this->request->param('id', 0, 'intval');
        if (empty($id)) {
            $this->error('product not exits');
        }
        $product = $productModel->where('id', $id)->find();

        if (empty($product)) {
            $this->error('product not exits');
        }

        $this->assign('product', $product);

        $productCategoryModel = new ProductCategoryModel();
        $category = $productCategoryModel->where('id', $product['category_id'])->find();
        $this->assign('category', $category);

        // 产品设置
        $product_setting = cmf_get_option('product_setting');
        $this->assign('product_setting', $product_setting);

        // 推荐产品
        $recommended_list = $productModel->field('id,title,thumbnail')->where([['id', '<>', $id], ['is_recommended', '=', 1]])->orderRaw('RAND()')->limit(12)->select();
        $this->assign('recommended_list', $recommended_list);

        $this->getBanner(2);

        return $this->fetch(':product-info');
    }

    public function international()
    {
        $service_site = cmf_get_option('service_site');
        $this->assign('service_site', $service_site);

        $this->getBanner(3);

        return $this->fetch(':international');
    }

    public function service()
    {
        $service_site = cmf_get_option('excellent_service_site');
        $this->assign('service_site', $service_site);

        $this->getBanner(3);

        return $this->fetch(':service');
    }

    public function download(){
        // 下载列表
        $portalPostModel = new PortalPostModel();
        $list = $portalPostModel->where('post_type', 3)->field('id,post_title,more')->select();
        $this->assign('list', $list);

        $this->getBanner(3);

        return $this->fetch(':download');
    }

    // 创新
    public function innovate()
    {
        $postModel = new PortalPostModel();
        $list = $postModel->where('post_type', 4)->field('id,post_title,post_excerpt,more')->select();
        $this->assign('list', $list);

        $innovate_site = cmf_get_option('innovate_site');
        $this->assign('innovate_site', $innovate_site);

        $this->getBanner(4);

        return $this->fetch(':innovate');
    }

    // 智造
    public function intelligent()
    {

        $portalCategoryModel = new PortalCategoryModel();
        $list = $portalCategoryModel->where('type', 5)->field('id,name')->select();
        $postModel = new PortalPostModel();
        foreach ($list as $k => $v) {
            $list[$k]['posts'] = $postModel->alias('a')->join('cmf_portal_category_post b','a.id=b.post_id')->where('a.post_type',5)->where('b.category_id', $v['id'])->field('a.id,post_title,post_excerpt,more')->select();
        }
        $this->assign('list', $list);

        $this->getBanner(4,1);

        return $this->fetch(':intelligent');
    }


    // 匠心
    public function ingenuity()
    {

        $ingenuity_site = cmf_get_option('ingenuity_site');
        $this->assign('ingenuity_site', $ingenuity_site);

        $postModel = new PortalPostModel();
        $list = $postModel->where('post_type', 6)->field('id,post_title,post_excerpt,more')->select();
        $this->assign('list', $list);

        $portalCategoryModel = new PortalCategoryModel();
        $ingenuity_category = $portalCategoryModel->where('type', 7)->field('id,name,description')->select();
        $postModel = new PortalPostModel();
        foreach ($ingenuity_category as $k => $v) {
            $ingenuity_category[$k]['posts'] = $postModel->alias('a')->join('cmf_portal_category_post b','a.id=b.post_id')->where('a.post_type',7)->where('b.category_id', $v['id'])->field('a.id,post_title,post_excerpt,more')->select();
        }
        $this->assign('ingenuity_category', $ingenuity_category);

        $this->getBanner(4,2);

        return $this->fetch(':ingenuity');
    }


    // 品质
    public function quality()
    {
        $quality_site = cmf_get_option('quality_site');
        $this->assign('quality_site', $quality_site);

        $this->getBanner(4,3);

        $postModel = new PortalPostModel();
        $list = $postModel->where('post_type', 8)->field('id,post_title,post_excerpt,more')->select();
        $this->assign('list', $list);

        return $this->fetch(':quality');
    }


    // 公司介绍
    public function introduction()
    {
        $about_site = cmf_get_option('about_site');
        $this->assign('about_site', $about_site);

        $this->getBanner(5);

        return $this->fetch(':introduction');
    }

    // 公司形象
    public function corporate()
    {

        $about_site = cmf_get_option('about_site');
        $this->assign('about_site', $about_site);

        $this->getBanner(5,1);

        return $this->fetch(':corporate');
    }

    // 联系我们
    public function contact()
    {
        $this->getBanner(6);

        return $this->fetch(':contact');
    }


    // 提交表单
    public function inquiry()
    {
        if (!$this->request->isPost()) {
            $this->error('Invalid request');
        }
        $data = $this->request->param();
        $InquiryValidate = new InquiryValidate();
        if (!$InquiryValidate->check($data)) {
            $this->error($InquiryValidate->getError());
        }
        Db::startTrans();
        try {
            // 保存消息
            $messageModel = new MessageModel();
            $ip = cmf_client_ip();
            $messageModel->saveMessage($ip,$data);
            $message_id = $messageModel->id;

            $UserAccessLogModel = new UserAccessLogModel();
            $UserAccessLogModel->saveLog($ip,$message_id);

            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($data['type'] == 3) {
            session('user_download', 1);
        }
        $this->success('submit success', '', ['session' => session('user_download')]);
    }

}
