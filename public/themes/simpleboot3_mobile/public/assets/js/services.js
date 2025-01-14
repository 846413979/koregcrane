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
            $('#feedback_type').val(3);
            $('.popover_wrap').show();
            $('#file_url').val(url);
            return
        }

        window.open(url);
    })



    
});