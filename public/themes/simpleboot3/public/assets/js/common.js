$(document).ready(function () {
    // 导航栏效果
    $('.header').hover(function () {
        $(this).find('.logo img').attr('src', $(this).find('img').data('active_url'));
        $(this).css('backgroundColor', '#fff');
        $(this).find('a.nav-title').css('color', '#333333')
        $(this).find('.nav-line').css('backgroundColor', '#000');
    }, function () {
        if ($(window).scrollTop() !== 0) {
            return;
        }
        $(this).find('.logo img').attr('src', $(this).find('img').data('default_url'));
        $(this).css('backgroundColor', 'transparent');
        $(this).find('a.nav-title').css('color', '#fff')
        $(this).find('.nav-line').css('backgroundColor', '#fff');
    });

    // 子导航右侧垂直居中
    $('.nav .nav-sub .nav-sub-right').each(function () {
        let item_len = $(this).find('.nav-sub-item').length;
        let width = item_len * 44 - 20;
        let margin_top = (240 - width) / 2;
        $(this).css('marginTop', margin_top + 'px');
    })

    // 子导航显示
    $('.header .nav ul li').hover(function () {
        $(this).find('.nav-sub').show();
    }, function () {
        $(this).find('.nav-sub').hide();
    })

    // 检查是否在页面顶部
    $(window).scroll(function () {
        let $header = $('.header');
        if ($(window).scrollTop() === 0) {
            $header.find('.logo img').attr('src', $header.find('img').data('default_url'));
            $header.css('backgroundColor', 'transparent');
            $header.find('a.nav-title').css('color', '#fff')
            $header.find('.nav-line').css('backgroundColor', '#fff');
        } else {
            $header.find('.logo img').attr('src', $header.find('img').data('active_url'));
            $header.css('backgroundColor', '#fff');
            $header.find('a.nav-title').css('color', '#333333')
            $header.find('.nav-line').css('backgroundColor', '#000');
        }
    });

    // 初始加载时检查一次
    $(window).trigger('scroll');

    $('.right_fix_top').click(function () {
        $('html,body').animate({scrollTop: 0}, 500);
    })


    $('.footer-feedback-btn').click(function () {
        let name = $('input[name=footer-name]').val();
        let email = $('input[name=footer-email]').val();
        let message = $('input[name=footer-message]').val();
        if (name == '' || email == '' || message == '') {
            alert('Please fill in the complete information')
            return;
        }
        let data = {
            name: name,
            email: email,
            content: message,
            type: 2,
        }
        $.ajax({
            url: '/portal/index/inquiry',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.code == 1) {
                    alert('submit success');
                } else {
                    alert('submit failed:' + res.msg);
                }
            }
        })
    })


})