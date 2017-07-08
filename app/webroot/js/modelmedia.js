// Model - load more instagram media
jQuery('document').ready(function(){
    if(jQuery('#addpostbtn').length>0){
        jQuery('#addpostbtn').click(function(){
            var url = encodeURIComponent($(this).data('url'));
            jQuery.ajax({
                type: 'get',
                url: 'load_more',
                data: { url : url },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    jQuery('#addpostbtn').button('荷重');
                },
                success: function(response) {
                    var response = jQuery.parseJSON(response);
                    jQuery('#addpostbtn').button('reset');
                    if (response.error) {
                        alert(response.error);
                        console.log(response.error);
                    }

                    // append post into list
                    if (response.posts) {
                        for (var i = 0, len = response.posts.length; i < len; i++) {
                            //var content = '<div class="col-sm-3"><img class="img-responsive" src="'+response.posts[i].images.thumbnail.url+'" /></div>';
//                            var content = '<div class="col-xs-6 col-sm-2 top20"><a href="add/'+response.posts[i].id+'"><img class="img-responsive" src="'+response.posts[i].images.thumbnail.url+'" ></a>';
                            var date = new Date(parseInt(response.posts[i].created_time) * 1000);
                            var caption_text = 'キャプションが有りません';
                            if(response.posts[i].caption )
                                caption_text = response.posts[i].caption.text;
                                    
                            var content = '<div class="col-xs-6 col-sm-2 top20">'
                                    +'<div class="center-block ' + response.posts[i].div_class + '"'
                                    + ' data-url="/admin/posts/add/'+response.posts[i].id+'" data-ajax-url="/admin/posts/checkmedia/'+response.posts[i].id+'">'
                                +'<span class="date_post">'+date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate()+'</span>'
                                +'<img src="'+response.posts[i].images.thumbnail.url+'" class="img-responsive post_img" alt=""><ul class="post_info">'
                                    +'<li class="like">'+response.posts[i].likes.count+'</li>'
                                    +'<li class="comments">'+response.posts[i].comments.count+'</li>'
                                +'</ul>'
                            +'</div>'
                            +'<button type="button" class="btn btn-default detail" data-toggle="modal" data-target=".post_instagram_'+response.posts[i].id+'">詳細</button>'
                            
                            +'<div class="modal fade post_instagram_'+response.posts[i].id+'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">'
                                +'<div class="modal-dialog modal-lg">'
                                  +'<div class="modal-content">'
                                    +'<div class="modal-header">'
                                        +'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'
                                        +'<h4 class="modal-title" id="myModalLabel">詳細</h4>'
                                    +'</div>'
                                      +'<div class="modal-body">'
                                        +'<div class="col-xs-12 col-sm-7">'
                                            +'<img src="'+response.posts[i].images.standard_resolution.url+'" class="img-responsive" alt=""></div>'
                                          +'<div class="col-xs-12 col-sm-5">'
                                            +'<p><b>キャプション:</b> '+caption_text+'</p>'
                                            +'<p><b>投稿日:</b>'+date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate()+' '+date.getHours()+' '+date.getMinutes()+'</p>'
                                            +'<ul class="post_info">'
                                                +'<li class="like">'+response.posts[i].likes.count+'</li>'
                                                +'<li class="comments">'+response.posts[i].comments.count+'</li>'
                                            +'</ul>'
                                          +'</div>'
                                    +'</div>'
                                      +'<div class="clearfix"></div>'
                                    +'<div class="modal-footer">'
                                        +'<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>'
                                    +'</div>'
                                  +'</div>'
                                +'</div>'
                            +'</div>'
                        +'</div>';
                            jQuery(content).hide().appendTo("#mlist").fadeIn(1000);
                        }
                        // fix check on mobile
                        if(!(typeof response.pagination.next_url == "undefined")) {
                            jQuery('#addpostbtn').data('url', response.pagination.next_url);

                        } else {
                            jQuery('#addpostbtn').remove();
                        }

                    }
                },
                error: function(e) {
                    alert("An error occurred: " + e.responseText.message);
                    console.log(e);
                }
            });
        });
    };

    $(document).on('click', ".top20 div.post", function() {
        $('.top20 div.post').removeClass('active');
        if(!$(this).hasClass('active')){
            $(this).addClass('active');
            var url = $(this).attr('data-url');
            var ajax_url = $(this).attr('data-ajax-url');
            $('#next_step').attr('href',url).attr('data-ajax-url',ajax_url).attr('disabled',false);
        }
    });
    $(document).on('click', ".post-existed", function() {
        alert("メディアは存在しました。");
    });

    $('#refresh').click(function(){
        location.reload();
    });

    $('#next_step').click(function(){
        var ajax_url = $('#next_step').attr('data-ajax-url');
        var url = $('#next_step').attr('href');
        var url_err = $('#next_step').attr('url_err');
        $('#next_step').removeAttr('href');
        $.ajax({
            method: 'POST',
            url: ajax_url,
            success: function (data, textStatus, jqXHR) {
                if(data === 'true'){
                    alert('メディアは存在しました。');
                }else if(data === 'false'){
                    $('#next_step').attr('href',url);
                    window.location = url;
                }else{
                    window.location = url_err;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                window.location = url_err;
            }
        });
    });

});