$(document).ready(function () {
    // 热门产品
    $('.product-line ul li').click(function () {
        let index = $(this).index();
        $('.product-content .product-content-ul').animate({'marginLeft': -1440 * index + 'px'});
        $(this).addClass('active').siblings().removeClass('active');
    })
})