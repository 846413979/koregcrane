function submit() {
    let name = $('.contact-message-right-name').val();// 姓名
    let phone = $('.contact-message-right-phone').val();// 手机号
    let email = $('.contact-message-right-email').val();// 邮箱
    let content = $('.contact-message-right-message').val();// 内容

    let data = {
        name: name,
        phone: phone,
        email: email,
        content: content,
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
                $('.popover_wrap').hide();
            } else {
                alert('submit failed:' + res.msg);
            }
        }
    })
}
