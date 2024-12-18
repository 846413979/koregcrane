$(document).ready(function() {
    $('.download_content_main_list_li').hover(function() {
        $(this).find('.download_content_main_list_li_foot_eye img').attr('src', $(this).find('.download_content_main_list_li_foot_eye img').data('active_img'));
    }, function() {
        $(this).find('.download_content_main_list_li_foot_eye img').attr('src', $(this).find('.download_content_main_list_li_foot_eye img').data('default_img'));
    });


    $('.download_content_main_list_li').click(function (){
        let session = $('#session').val();
        console.log(session)
        let url = $(this).data('url');
        if(session===''){
            $('.popover_wrap').show();
            $('#file_url').val(url);
            return
        }

        window.open(url);
    })



    $('#popover-submit').click(function (){
        let name = $('#popover-name').val();// 姓名
        let phone = $('#popover-phone').val();// 手机号
        let email = $('#popover-email').val();// 邮箱
        let content = $('#popover-content').val();// 内容

        let $file_url = $('#file_url');
        let file = $file_url.val();

        let data = {
            name: name,
            phone: phone,
            email: email,
            content: content,
            type:3,
        }

        $.ajax({
            url: '/portal/index/inquiry',
            type: 'POST',
            data:data,
            dataType: 'json',
            success: function (res) {
                if (res.code == 1) {
                    alert('submit success');
                    $('#session').val(res.data.session)
                    $('.popover_wrap').hide();
                    $file_url.val('');
                    window.open(file)
                } else {
                    alert('submit failed:'+res.msg);
                }
            }
        })
    })

    $('.popover_wrap .popover_container .popover_close').click(function (){
        $('#file_url').val('');
        $('.popover_wrap').hide();
    })
});