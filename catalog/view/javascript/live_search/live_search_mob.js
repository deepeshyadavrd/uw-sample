$(document).ready(function(){
    $('#searchinputmob').keyup(function() {
        // console.log(options);
// alert('hello');
        // Initializing drop down list
        var html = '<div class="live-searchmob"><ul></ul><div class="result-text"></div></div>';
        if (!$('.mob-search-content').find(".live-searchmob").length) {
            $("#searchmob").after(html);
        }

        $("#searchinputmob").autocomplete({
            'source': function(request, response) {
                var filter_name = $("#searchinputmob").val();
                var cat_id = 0;
                var live_search_min_length = 2;
                if (filter_name.length < live_search_min_length) {
                    $('.live-searchmob').css('display','none');
                }
                else{
                    var live_search_href = '?route=product/live_search&filter_name=';
                    var all_search_href = '?route=product/search&search=';
                    if(cat_id > 0){
                        live_search_href = live_search_href + encodeURIComponent(filter_name) + '&cat_id=' + Math.abs(cat_id);
                        all_search_href = all_search_href + encodeURIComponent(filter_name) + '&category_id=' + Math.abs(cat_id);
                    }
                    else{
                        live_search_href = live_search_href + encodeURIComponent(filter_name);
                        all_search_href = all_search_href + encodeURIComponent(filter_name);
                    }

                    var html  = '<li style="text-align: center;height:10px;">';
                        html += '<img class="loading" src="catalog/view/javascript/live_search/loading.gif" />';
                        html += '</li>';
                    $('.live-searchmob ul').html(html);
                    $('.live-searchmob').css('display','block');

                    $.ajax({
                        url: live_search_href,
                        dataType: 'json',
                        success: function(result) {
                            var products = result.products;
                            $('.live-searchmob ul li').remove();
                            $('.result-text').html('');
                            if (!$.isEmptyObject(products)) {
                                var show_image       = 1;
                                var show_price       = 1;
                                var show_description = 0;
                                var show_add_button  = 1;

                                $('.result-text').html('<a href="'+all_search_href+'" class="view-all-results">View all products ('+result.total+')</a>');
                                $.each(products, function(index,product) {
                                    var html = '<li>';
                                        // show_add_button
                                    if(show_add_button){
                                        html += '<div class="product-add-cart">';
                                        html += '<a href="javascript:;" onclick="cart.add('+product.product_id+', '+product.minimum+');" class="btn btn-primary">';
                                        html += '<i class="fa fa-shopping-cart"></i>';
                                        html += '</a>';
                                        html += '</div>';
                                    }
                                        html += '<div>';
                                        html += '<a href="' + product.url + '" title="' + product.name + '">';
                                    // show image
                                    if(product.image && show_image){
                                        html += '<div class="product-image"><img alt="' + product.name + '" src="' + product.image + '"></div>';
                                    }
                                    // show name & extra_info
                                    html += '<div class="product-name">' + product.name ;
                                    // if(show_description){
                                    //     html += '<p>' + product.extra_info + '</p>';
                                    // }
                                    html += '</div>';
                                    // show price & special price
                                    if(show_price){
                                        if (product.special) {
                                            html += '<div class="product-price"><span class="special">' + product.price + '</span><span class="price">' + product.special + '</span></div>';
                                        } else {
                                            html += '<div class="product-price"><span class="price">' + product.price + '</span></div>';
                                        }
                                    }
                                    html += '</a>';
                                    html += '</div>';

                                    html += '</li>';
                                    $('.live-searchmob ul').append(html);
                                });
                            } else {
                                var html = '';
                                html += '<li style="text-align: center;height:10px;">';
                                html += "no match found";
                                html += '</li>';

                                $('.live-searchmob ul').html(html);
                            }
                            // $('.live-search ul li').css('height',live_search.height);
                            $('.live-searchmob').css('display','block');

                            return false;
                        }
                    });

                }
            },
            'select': function(product) {
                $(live_search_mob.selector).val(product.name);
            }
        });

        $(document).bind( "mouseup touchend", function(e){
            var container = $('.live-searchmob');
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
            }
        });
    }

);
});