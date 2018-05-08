$("body").keydown(function() {
     if (event.keyCode == "13") {//keyCode=13是回车键
         $('#shorten').click();
         return false;
     }
 });

$('#shorten').click(function(){
    var url = $('#url').val();
    var formhash = $('#formhash').val();
    if(validator.isURL(url)){
        $('#loading_shorten #loading_shorten_title').html('正在生成短网址...');
        $('#loading_shorten #loading_shorten_value').html('<span class="am-icon-spinner am-icon-spin"></span>');
        var $modal = $('#loading_shorten');
        $modal.modal();

        $.post(
            'result?type=shorten',
            {url:url,formhash:formhash},
            function (data) {
                $modal.modal('close');
                if (data.status == 1) {
                    $('#url').val(data.short_url);
                } else {
                    $('.am-modal-bd').html(data.info);
                    $('#error_alert').modal();
                }
            },'json'
        )
    }else{
        $('.am-modal-bd').html('请输入正确的url');
        $('#error_alert').modal();
    }
});

$('#expand').click(function() {
    var url = $('#url').val();
    var formhash = $('#formhash').val();
    var $modal = $('#loading_expand');

    $.post(
        'result?type=expand',
        {url:url,formhash:formhash},
        function(data) {
            if (data.status == 1) {
                $('#url').val(data.url);
            } else {
                $('.am-modal-bd').html(data.info);
                $('#error_alert').modal();
            }
        },'json'
    )
});
