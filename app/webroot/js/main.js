jQuery('document').ready(function($) {
//    menu
    var icon = $('#snap-toggle-btn');
    $('#snap-toggle-btn').addClass('navi-add-togge');
    $('nav#menu').removeClass('hidden');
    $('nav#menu').mmenu({
        slidingSubmenus: false
    });
    $('#menu').data('mmenu').bind('opened', function () {
        icon.removeClass('navi-add-togge');
        icon.addClass('navi-delete-togge');
    });
    $('#menu').data('mmenu').bind('closed', function () {
        icon.removeClass('navi-delete-togge');
        icon.addClass('navi-add-togge');
    });

//end menu
    $('body').on('click','.followed',function(){
        var elm_followed = $(this);
        var id_instagram = $(this).attr('id-istagram');
        var url_action = $(this).attr('url-action');
        var follow = $(this).attr('status-follow');
        if(follow == 1){
            elm_followed.removeClass('active');
            elm_followed.addClass('status-notlike'); 
        }else{
           elm_followed.addClass('active'); 
           elm_followed.addClass('status-notlike');
        }
        $.ajax({
            url: url_action,
            type: 'POST',
            data: {id_instagram: id_instagram, status_follow: follow},
            success: function (data, textStatus, jqXHR) {
                if(data !== 'false'){
                    var action_follows = JSON.parse(data);
                    if(action_follows['status'] === 'follow'){
                        elm_followed.addClass('status-like');
                        elm_followed.removeClass('status-notlike');
                        elm_followed.attr('status-follow', 1);
                    }else{
                        elm_followed.removeClass('status-like');
                        elm_followed.addClass('status-notlike'); 
                        elm_followed.attr('status-follow', 0);
                    }
                    $('#model-followed'+id_instagram).html( action_follows['total_follows']);
                }else{
                    if(follow == 1){
                        elm_followed.addClass('status-like'); 
                        elm_followed.removeClass('status-notlike');
                    }else{
                       elm_followed.removeClass('status-like'); 
                       elm_followed.addClass('status-notlike'); 
                    }
                    alert('フォローにはエラーが発生しました。');
                }
                elm_followed.css('pointer-events','auto');
                elm_followed.children('span').remove();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if(follow == 1){
                    elm_followed.addClass('active'); 
                }else{
                   elm_followed.removeClass('active'); 
                }
                alert('フォローにはエラーが発生しました。');
                elm_followed.css('pointer-events','auto');
            },
            beforeSend:function (xhr) {
                elm_followed.css('pointer-events','none');
                elm_followed.css('position','relative');
                elm_followed.prepend('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate followed_load"></span>');
            }
        });
    
    });
  
    
    $('.btnLike').click(function(){
        var btn_liked = this;
        var liked = $(btn_liked).attr('liked');
        var url = $(btn_liked).attr('url') + '/' + liked;
        $.ajax({
            type: 'Post',
            url: url,
            data: {'liked': liked},
            success: function (data, textStatus, jqXHR) {
                var data_rs = data;
                if(data_rs['status'] === 'like'){
                    $(btn_liked).attr('liked', 1);
                    //$(btn_liked).addClass('liked');
                    //$(".img-icon-like").attr("src","/images/follow.png");
                    $(btn_liked).text('LIKED');
                }else if(data_rs['status'] === 'unlike'){
                    $(btn_liked).attr('liked', 0);
                    //$(btn_liked).removeClass('liked');
                     //$(".img-icon-like").attr("src","/images/nofollow.png");
                    $(btn_liked).text('LIKE');
                }else{
                    alert('エラー');
                }
                $('.total-like').html(data_rs['total_likes']);
                $(btn_liked).css('pointer-events','auto');
                $(btn_liked).children('span').remove();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('エラー: '+ textStatus);
                if(liked){
                    $(btn_liked).addClass('liked');
                }else{
                    $(btn_liked).removeClass('liked');
                }
                $(btn_liked).css('pointer-events','auto');
            },
            beforeSend:function (xhr) {
                $(btn_liked).css('pointer-events','none');
                /*$(btn_liked).prepend('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate" style="font-size: 40px;"></span>');*/
            }
        });
    });
    var loading = false;
    $(window).scroll(function(){
        if(!$('#load_more_post').length){return false;}
        var btn_load_more = $('#load_more_post');
        btn_load_more.hide();
        if(($(window).scrollTop() + $(window).height() >= $(document).height() - 150) && !loading){
            loading = true;
            $(this).delay(500).queue(function (){
                load_more(btn_load_more);
                $(this).dequeue();
            });
        }
    });
    
    function load_more(btn_load_more){
        var url = btn_load_more.attr('url');
        if(current_page < count_page){
            btn_load_more.show();
            current_page++;
            $.ajax({
                method:'POST',
                url: url,
                data:{page: current_page},
                success: function (data, textStatus, jqXHR) {
                    $('#wc .container-fluid.last').removeClass('last');
                    if(current_page >= count_page){
                        btn_load_more.hide();
                    }
                    btn_load_more.html('');
                    if($('#wc').length>0) {
                        jQuery(data).each(function () {
                            var $self = $(this);
                            $self.css('opacity', 0);
                            $('#wc').append($self);
                            imagesLoaded('#wc', function () {
                                wookmark.initItems();
                                wookmark.layout(true);
                                window.setTimeout(function () {
                                    $self.css('opacity', 1);
                                }, 10);
                            });
                        });
                    }

                    if($('.list').length>0) $('.list').append(data);
                    loading = false;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    btn_load_more.html('');
                    current_page--;
                },
                beforeSend: function (xhr) {
                    btn_load_more.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> ローディング...');
                }
            });
        }else{
            btn_load_more.hide();
        }
    }

    $('#load-more-comment').click(function(){
        load_comment('.btn-comment', 0,0);
    });
    $('#form_add_comment').submit(function(event){
        event.preventDefault();
        var text_comment = $('#text_comment').val();
        if(text_comment === ''){
            $('#text_comment').parent('.form-group').addClass('has-error');
        }else{
            load_comment('.btn-comment',1,text_comment);
            $('#text_comment').parent('.form-group').removeClass('has-error');
        }
    });
    
    
    
    function load_comment(element, flag,text_comment){
        var page = $(element).attr('data-page');
        var url = $(element).attr('data-url');
        var count = $(element).attr('data-count');
        var html_more = $('#load-more-comment').html();
        var data = {page: page,count: count,flag:flag,text_comment:text_comment};
        $.ajax({
            method: 'POST',
            url: url,
            data:data,
            success: function (data, textStatus, jqXHR) {
                if(data['count_show_more'] <= 0){
                    $('#load-more-comment').hide();
                }else{
                      $('#load-more-comment').show();
                }
                $(element).attr('data-page', data['page_next']);
                if(!flag){
                   $('#load-more-comment').html(data['count_show_more'] + 'more comments');
//                   $('p.comment-info:first').before(data['html_comment']); 
                   $('#container_comments').html(data['html_comment']); 
                   $('#load-more-comment').css('pointer-events','auto');
                   $('.btn-comment').html('送信');
                }else{
                     $('#load-more-comment').html(data['count_show_more'] + 'more comments');
                   $('#container_comments').html(data['html_comment']); 
                   $('.btn-comment').html('送信');
                   $('#text_comment').val('');
                   $('.btn-comment').css('pointer-events','auto');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('エラー');
                if(!flag){
                    $('#load-more-comment').css('pointer-events','auto');
                    $('#load-more-comment').html(html_more);
                }else{
                    $('.btn-comment').css('pointer-events','auto');
                    $('.btn-comment').html('送信');
                }
            },
            beforeSend: function (xhr) {
                if(!flag){
                    $('#load-more-comment').css('pointer-events','none');
                    $('#load-more-comment').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> ローディング...');
                }else{
                    $('.btn-comment').css('pointer-events','none');
                    $('.btn-comment').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                }
            }
        });
    }
});
