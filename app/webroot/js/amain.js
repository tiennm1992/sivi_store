// setup default ajax setting
/*$(document).ajaxStart(function () {
    $.blockUI({ css: {
        border: 'none',
        padding: '15px',
        backgroundColor: '#000',
        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
        opacity: .5,
        color: '#fff'
    } });
}).ajaxError(function( event, jqxhr, settings, thrownError ) {

}).ajaxStop(function () {
    setTimeout($.unblockUI, 2000);
});*/
  
$('.update_status_comment').click(function(){
     var url_action = $(this).attr('url-action');
     var id = $(this).attr('_id');
    var data= {action: 'status'};
         $.ajax({
            method: 'POST',
            url: url_action,
            data:data,
            success: function (data, textStatus, jqXHR) {
                if(data['status']!=1){
                  $('#content_stauts_'+id).html('<span class="label  btn-danger">表示</span>'); 
                  
                   $('.content_action_'+id).removeClass('glyphicon-remove'); 
                   $('.content_action_'+id).addClass('glyphicon-eye-open');
                   
                     
                   $('.btn_'+id).removeClass('btn-danger');  
                    $('.btn_'+id).addClass('btn-info');
                    
                 
                }else{
                  $('#content_stauts_'+id).html('<span class="label  btn-info">非表示</span>');   
                   $('.content_action_'+id).removeClass('glyphicon-eye-open'); 
                   $('.content_action_'+id).addClass('glyphicon-remove'); 
                   
                 
                    
                    $('.btn_'+id).addClass('btn-danger');  
                    $('.btn_'+id).removeClass('btn-info');
                 
                }
            },
            
        });
    });
$('#search-button').click(function(e){
    $('.glyphicon-collapse').toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
})
function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
        .removeAttr('checked').removeAttr('selected');
}
$("#reset").click(function() {
    resetForm($('#form_site'));
    return false;
});
function ajax_import_csv(id_form, url_check, url_import, url_error, id_div_error, id_row_skip, id_btn_hide,ec_el, im_el){
    $('#'+id_form).submit(function(e){
        e.preventDefault();
        var val_im_el = $('input[type=radio]:checked');
        var val_ec_el = $('select>option:selected').text();
        var url = url_check;
        if($('#'+id_row_skip).length > 0){
            url = url_import;
        }else if(val_im_el.hasClass('for_ecsite')){
            var cf = confirm('本当に'+val_ec_el+'というecサイトへ商品をインポートしますか?');
            if(!cf) return false;
        }
        $.ajax( {
          url: url,
          type: 'POST',
          data: new FormData( this ),
          processData: false,
          contentType: false,
          async: true,
          cache: false,
          success: function(data, status, jqXHR){
              if(data.indexOf(' *^^InternalErrorException:500^^* ')>= 0){
                  window.location.assign(url_error);
              }else{
                  $('#'+id_div_error).html(data);
                  $('#'+id_btn_hide).html('インポートをチェックする。');
                  chang_element(id_btn_hide,ec_el,im_el,true);
              }
          },
          error: function(rs, err, text){
              window.location.assign(url_error);
          },
          beforeSend:function(){
              $('#'+id_btn_hide).attr('disabled', false);
              $('#'+id_btn_hide).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> ローディング...');
          }
        } );
        return false;
    });
}
function change_btn_import(id_btn_hide, div_result, ec_el, im_el){
    $(document).on('change','#filecsv',function (){
        chang_element(id_btn_hide,ec_el,im_el,false);
        $('#'+div_result).html('');
    });
} 
function chang_element(id_btn_hide,ec_el,im_el, val){
    $('#'+id_btn_hide).attr('disabled', val);
    var val_ec_el = $('#'+ec_el).val();
    var val_im_el = $('input[type=radio]:checked').val();
    $('input[type=hidden]').attr('disabled', !val);
    if($('input[type=radio]:checked').hasClass('for_ecsite')){
        $('#'+ec_el).attr('disabled', val);
    }else{
        $('.ecsite_id_hidden').attr('disabled', val);
    }
    $('.'+im_el).attr('disabled', val);
    $('.'+ec_el+'_hidden').val(val_ec_el);
    $('.'+im_el+'_hidden').val(val_im_el);
}
$('.import_for').change(function(){
    if($(this).hasClass('for_ecsite')){
        $('#ecsite_id').attr('disabled', false);
        $('.ecsite_id_hidden').attr('disabled', false);
        $('#ecsite_id').parents('.dsp-ecsite').show();     
    }else{
        $('#ecsite_id').attr('disabled', true);
        $('.ecsite_id_hidden').attr('disabled', true);
        $('#ecsite_id').parents('.dsp-ecsite').hide();
    }
});

