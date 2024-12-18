
var width = window.innerWidth;
// 设置html的font-size为视口宽度的1/10
document.documentElement.style.fontSize = (width / 37.5) + 'px';
$(document).ready(function () {

    // 导航栏显示
    $('.header .nav .nav-btn').click(function (){
        $(this).siblings('.nav-list').toggle();
    })

    // 子导航显示
    $('.header .nav ul li .nav-sub-btn').click(function (){
        if ($(this).hasClass('active')){
            $(this).removeClass('active');
            $(this).siblings('.nav-sub').hide();
            $(this).html('+')
        }else{
            $(this).addClass('active');
            $(this).siblings('.nav-sub').show();
            $(this).html('-')
        }
    })

    $('.footer-feedback-btn').click(function (){
        let name=$('input[name=footer-name]').val();
        let email=$('input[name=footer-email]').val();
        let message=$('input[name=footer-message]').val();
        if(name==''||email==''||message=='') {
            alert('请填写完整信息')
            return;
        }
        let data = {
            name: name,
            email: email,
            content: message,
            type:2,
        }
        $.ajax({
            url: '/portal/index/inquiry',
            type: 'POST',
            data:data,
            dataType: 'json',
            success: function (res) {
                if (res.code == 1) {
                    alert('submit success');
                } else {
                    alert('submit failed:'+res.msg);
                }
            }
        })
    })


})