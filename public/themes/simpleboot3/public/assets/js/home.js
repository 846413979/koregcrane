$(document).ready(function () {
    // 热门产品
    $('.product-line ul li').click(function () {
        let index = $(this).index();
        let w = $('.product-content').width();
        $('.product-content .product-content-ul').animate({'marginLeft': -w * index + 'px'});
        $(this).addClass('active').siblings().removeClass('active');
    })
})