$(document).ready(function () {
    $('.innovate-content-list ul').on('click', '.innovate-content-item', function () {
        let img = $('.innovate-content-img img');
        $(this).addClass('active').siblings().removeClass('active');
        img.attr('src', $(this).data('img'));
        img.attr('alt', $(this).find('.innovate-content-item-title').text());
        $(this).find('.innovate-content-item-btn').html('-');
        $(this).siblings().find('.innovate-content-item-btn').html('+');
    })

    $('.intelligent-title').on('click', '.intelligent-title-item', function () {
        $(this).addClass('active').siblings().removeClass('active');
        $('.intelligent-content-list').eq($(this).index()).addClass('active').siblings().removeClass('active');
    })


    $('.ingenuity-list .ingenuity-list-content .ingenuity-list-line ul').on('click', '.ingenuity-list-line-item', function () {
        if ($(this).hasClass('active')) {
            return false
        }
        let index = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        let $ingenuity_item = $('.ingenuity-list-content .ingenuity-item')
        $ingenuity_item.eq(index).addClass('active').siblings().removeClass('active');
        let img = $ingenuity_item.eq(index).data('img');
        $('.ingenuity-list .ingenuity-img img').attr('src',img);
    })

    $('.ingenuity-category').on('click','.ingenuity-category-item',function (){
        if ($(this).hasClass('active')) {
            return false
        }
        $(this).addClass('active').siblings().removeClass('active');
        $('.ingenuity-category-list').eq($(this).index()).addClass('active').siblings().removeClass('active')
    })


    $('.quality-content-pics-list ul').on('click','.quality-content-pics-item',function (){
        if ($(this).hasClass('active')) {
            return false
        }
        $(this).addClass('active').siblings().removeClass('active');
        $('.quality-content-img .quality-content-img-desc').html($(this).data('description'))
        $('.quality-content-img .quality-content-img-title').html($(this).find('.quality-content-pics-item-title').text())
        $('.quality-content-img img').attr('src',$(this).data('img'))
    })

});