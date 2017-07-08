jQuery(document).ready(function() {
    var Post  = function(options){
        // constructor
        this.options = jQuery.extend({

        }, options);
        this.promotionList = jQuery('input[name="_postpromotions"]').length == 1 ? jQuery.parseJSON(jQuery('input[name="_postpromotions"]').val()) : [];
        this.promotionInputElement = jQuery('#postpromotions');
        this.promotionSource = jQuery('#promotion-source');
        this.promotionDes = jQuery('#products-container');
        this.promotionAddMoreBtn = jQuery('.products-load-more > button');
        this.promotionAddBtnSelector = '.promotion > button';
        this.promotionRemoveBtnSelector = '.products > a.test';
        var self = this;
        // add promotion block event
        this.promotionAddMoreBtn.click(this.addMorePromotionBlock);
        // add promotion event
        this.promotionSource.on('click',this.promotionAddBtnSelector, function(event){
            // get promotion data from souce
            var img = jQuery(this).parent().find('img').attr("src");
            var proId = jQuery(this).parent().attr('id');
            var proUrl = jQuery(this).parent().attr('data-url');
            var title = jQuery(this).parent().find('p.pro-title').text();
            var short_url = proUrl.substring(0,21)+'...';
            var pp = {promotion_id : proId, img_url : img, ec_url: proUrl , title: title, short_url: short_url};

            if(self.checkPromotionExists({promotion_id: proId})){
                alert('アイテムが追加されます。');
                return;
            }
            var pl = self.promotionDes.children('.products').length;
            if( pl < 1 ) {
                alert('プロモーションボックスが有りません。追加して下さい。');
                // scroll to Des position
                jQuery('html,body').animate({scrollTop: self.promotionDes.offset().top - 10},'slow');
                return false;
            }
            var countEC = jQuery('#products-container').children('.products:empty').length;
            if(countEC > 0) {
                var this_div;
                if(self.promotionList.length>0){
                    this_div = self.promotionDes.children('.products:nth-child('+self.promotionList.length+')');
                    this_div.after(self.pItemTemplate({p : pp}));
                    //this_div.next().css({'border':'0px','margin-bottom': '5px'});
                } else {
                    self.promotionDes.find('.products:first-child').before(self.pItemTemplate({p : pp}));
                    //self.promotionDes.find('.products:first-child').css({'border':'0px','margin-bottom': '5px'});
                }
                // fix selector on mobile
                self.promotionDes.children('.products:empty:first').remove();
                self.promotionList.push(pp);
                self.updatePromotionInputElement();
                // trigger check value on promotion input element
                self.trigerFocusoutPromotionInputElement();
                jQuery(this).removeClass('btn-primary').addClass('disabled');
            } else{
                alert('プロモーションボックスが一杯になりました。追加して下さい。');
                // scroll to Des position
                jQuery('html,body').animate({scrollTop: self.promotionDes.offset().top - 10},'slow');
            }
        });
        // remove promotion event
        this.promotionDes.on('click', this.promotionRemoveBtnSelector, function(event){
            event.preventDefault();
            var confirm_rm = confirm('を本当に削除しますか?');
            if(confirm_rm){
                var proId = jQuery(this).parent().attr('data-proid');
                if(self.checkPromotionExists({promotion_id: proId})){
                    self.promotionList = _.without(self.promotionList, _.findWhere(self.promotionList, {promotion_id: proId}));
                    self.updatePromotionInputElement();
                    jQuery(this).parent().fadeOut('slow', function(){ jQuery(this).remove(); });
                    self.promotionInputElement.trigger('focusout');
                    $('#'+proId).children('button.btn-add').attr('disabled',false);
                }
            }
        });

        this.pItemTemplate = _.template('<div class="pull-left products" data-proid="<%= p.promotion_id %>"><a class="test" href="#" data-url="<%= p.ec_url %>"><img src="<%= p.img_url %>" width="50" title="<%= p.name %>" alt="<%= p.name %>"/></a></div>');
        this.pListTemplate= _.template('<% _.each(promotionList, function(p) { %><%= pItemTemplate({p : p}) %><% }); %>');

    };

    Post.prototype = {
        // implementation
        addMorePromotionBlock: function(event){
            event.preventDefault();
            var content = '';
            for(var i =0 ; i < 3; i++){
                content += '<div class="pull-left products"></div>';
            }
            jQuery(this).parent().before(content);
        },

        promotionListRender : function(){
            if(this.promotionDes.find('.products:first-child').length>0){
                this.promotionDes.find('.products:first-child').before(this.pListTemplate({promotionList: this.promotionList, pItemTemplate: this.pItemTemplate}));
            }else {
                this.promotionDes.prepend(this.pListTemplate({promotionList: this.promotionList, pItemTemplate: this.pItemTemplate}));
            }

        },
        init : function(){
            this.promotionListRender();
            this.updatePromotionInputElement();
        },

        trigerFocusoutPromotionInputElement: function() {
            this.promotionInputElement.trigger('focusout');
        },

        checkPromotionExists : function(pSearch){
            var isAdded = _.where(this.promotionList, pSearch);
            return isAdded.length>0 ? true : false ;
        },

        updatePromotionInputElement: function(){
            var idList = _.map(this.promotionList, function(p){ return p.promotion_id; });
            this.promotionInputElement.val(idList.toString());
        }
    };

    
    var post = new Post();
    post.init();

    // search promotion action
    jQuery('#search-tag').keypress(function(event) {
        var p = event.which;
        if(p==13){
            jQuery('#pro-search').trigger('click');
            event.preventDefault();
        }

    });
    jQuery('#pro-search').click(searchPromotion);
    // valid add post form
    
    jQuery('#post-form').validate({
        errorElement: 'lable',
        errorClass: 'control-label',
        ignore: [],
        rules : {
            postdate: {
                required : true,
                date : true
            },
/*            posttag: {
                required : true
                //selectcheck : true
            },*/
            postpromotions: {
                required : true
            }

        },
        messages: {
            postpromotions: {
                required: '<i class="fa fa-times-circle-o"></i> アイテムは、空、検索項目と、それを追加するだけです。'
            },
            postdate: {
                required: '<i class="fa fa-times-circle-o"></i> この項目は必須です。',
                date: '<i class="fa fa-times-circle-o"></i> 有効な日付を入力してください。'
            }
/*            posttag: {
                required: '<i class="fa fa-times-circle-o"></i> この項目は必須です。'
            }*/

        },
        highlight: function(element) {
            jQuery(element).closest('.form-group').removeClass('success').addClass('has-error');
        },
        success: function(element) {
            //.text('OK!')
            element.addClass('valid').closest('.form-group').removeClass('has-error').addClass('success');
            if(element.attr('id') == 'posttag') {
                jQuery('#posttagerror').remove();
            } else {
                element.addClass('valid').closest('label').remove();
            }

        },
        errorPlacement: function(error, element) {
            if (element.attr('id') == 'postdate') {
                error.insertAfter('#datetimepicker1');
            } else if (element.attr('id') == 'posttag') {
                element.closest('.col-sm-10').append('<div id="posttagerror">'+error[0].outerHTML+'</div>');
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(e) {
            $('#register-success').hide();
            $('#post-info').show();
            $(document).on('click','#pro-back',function (){
                $('#register-success').show();
                $('#post-info').hide();
            });
            var tagList = $('.bootstrap-tagsinput').children('.tag');
            var promotionList = $('#products-container').children('div.products');
            var date = $('input[name=postdate]').val();
            var description = $('textarea[name=description]').val();
            $('#date-info').html('<p style="margin:0px; padding:0px">'+date+'</p>');
            $('#description-info').html('<p style="margin:0px; padding:0px">'+description+'</p>');
            $('#tag-info').empty();
            $('#promotion-info').empty();
            $.each(tagList,function(key,val){
                $('#tag-info').append('<span class="tag label label-info" style="margin: 0 4px;display: inline-block;">'+$(val).text()+'</span>');
            });
            var pItemTemplate = jQuery("#p-review-data").html();
            jQuery("#promotion-info").html(_.template(pItemTemplate)({items:post.promotionList}));

            $('#pro-add').click(function (){
                e.submit();
            });

        }
    });

    // trigger focusout on posttag input element
    jQuery('#posttag').on('itemAdded', function(event) {
        jQuery('#posttag').trigger('focusout');

    }).on('itemRemoved', function(event) {
        jQuery('#posttag').trigger('focusout');
    });

    // setup date picker
    var pDate = jQuery('input[name="_postdate"]').length == 1 ? new Date(parseInt(jQuery('input[name="_postdate"]').val())) : new Date() ;
    jQuery('#datetimepicker1').datetimepicker(
        {
            format: 'YYYY/MM/DD HH:mm',
            defaultDate: pDate
        }
    )

    function searchPromotion(event, cpage){
        event.preventDefault();

        jQuery.ajax({
            type: 'post',
            url: '/admin/posts/get_promotion',
            data: { tag : jQuery('#search-tag').val(), cat1 : jQuery('#search-cat1').val(), cat2 : jQuery('#search-cat2').val(), 'cpage' : cpage},
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                //jQuery('#pro-search').button('loading');
            },
            success: function(response) {
                var response = jQuery.parseJSON(response);
                //jQuery('#pro-search').button('reset');
                if (response.error) {
                    alert(response.error);
                }

                // append promotion into search list
                if (response.pros && response.pros.length > 0) {
                    // get promotion template
                    var pTemplate = jQuery("#p-data").html();

                    var searchHeader =  '<div id="search-paging" class="row products form">' +
                                        '<div class="col-xs-12">' +
                                        '<div class="datagrid dataTables_paginate paging_bootstrap col-xs-12 col-sm-8">' +
                                        '<ul class="pagination">';
                    if(response.total_page > 1){
                        if(response.current_page == 1){
                            //searchHeader = searchHeader +'<li class="prev disabled"><a href="#">«</a></li>' ;
                        }else {
                            searchHeader = searchHeader + '<li class="prev"><a href="#">«</a></li>' ;
                        }
                        for (var i = 1; i <= response.total_page; i++){
                            if(response.current_page == i){
                                searchHeader = searchHeader + '<li id="'+i+'" class="active"><a href="#">'+i+'</a></li>';
                            } else {
                                searchHeader = searchHeader + '<li id="'+i+'"><a href="#">'+i+'</a></li>';
                            }

                        }
                        if(response.current_page == response.total_page){
                            //searchHeader = searchHeader + '<li class="next disabled"><a href="#">»</a></li>';
                        }else{
                            searchHeader = searchHeader + '<li class="next"><a href="#">»</a></li>';
                        }
                    }

                    searchHeader =  searchHeader + '</ul>' +
                                    '</div>' +
                                    '<div class="col-xs-12 col-sm-4 row-space">' +
                                    '<div id="rows_info_pag_demo_grid1" class="pull-right">' + response.total_row + '件中 ' + response.from + '-' + response.to + '件表示' + '(ページ'+response.current_page+'/'+response.total_page+')</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                    if(jQuery('#search-paging').length > 0){
                        jQuery('#search-paging').remove();
                    }
                    jQuery("#promotion-source").parent().before(searchHeader);
                    
                    jQuery("#promotion-source").html(_.template(pTemplate)({items:response.pros}));

                    jQuery('ul.pagination > li > a').click(function(event){
                        var page = jQuery(this).parent().attr('id');

                        if ( jQuery(this).parent().hasClass( "prev" ) ) {
                            page = parseInt(response.current_page) - 1;
                        }

                        if ( jQuery(this).parent().hasClass( "next" ) ) {
                            page = parseInt(response.current_page) + 1;
                        }

                        searchPromotion(event, page);
                    });

                } else{
                    if(jQuery('#search-paging').length > 0){
                        jQuery('#search-paging').remove();
                    }
                    jQuery("#promotion-source").html('<p>アイテムが見つかりません</p>');
                }
            },
            error: function(e) {
                alert("An error occurred: " + e.responseText.message);
            }
        });
    }
});
