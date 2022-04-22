(function ($) {
    $doc = $(document);

    $doc.ready(function () {

        var currenturl = window.location.href;

        /**
         * Get posts
         */
        function get_posts($params) {

            $container = $('#container-async-news');
            $content = $container.find('.content-news');
            $status = $container.find('.status');
            $pager = $container.find('.infscr-pager a');

            $status.text('Chargement des articles ...');

            /**
             * Reset infinite scroll
             */
            if ($params.page === 1 && $pager.length) {

                $pager.removeAttr('disabled').html();
            }

            if ($pager.length) {
                $method = 'infscr';
            } else {
                $method = 'pager';
            }


            /**
             * AJAX
             */
            $.ajax({
                url: webexprnews.ajax_url,
                data: {
                    action: 'do_filter_posts_mt_actualites',
                    nonce: webexprnews.nonce,
                    params: $params,
                    pager: $method
                },
                type: 'post',
                dataType: 'json',
                success: function (data, textStatus, XMLHttpRequest) {

                    if (data.status === 200) {
                        $('nav.pagination.infscr-pager').css('display','flex');

                        if ( data.found <= data.posts_per_page) {
                            $('nav.pagination.infscr-pager').css('display','none');
                        }


                        if (data.method === 'pager' || $params.page === 1) {
                            $content.html(data.content);
                        }
                        else {
                            $content.append(data.content);

                            numberitem = $('.content-news > div').length;

                            if (numberitem >= data.found) {
                                $('nav.pagination.infscr-pager').css('display','none');
                            }

                            if (data.next !== 0) {
                                $pager.attr('href', '#page-' + data.next);
                            }
                        }

                    } else if (data.status === 201) {


                        if (data.method === 'pager' ) {
                          $content.html(data.message);
                        }
                        else {
                            $pager.attr('href', '#page-2');
                        }

                        $content.html('<div class="no-news"> Aucun article trouvé </div>');
                        $('nav.pagination.infscr-pager').css('display','none');

                    } else if (data.status === 501) {
                        $('a[data-term="all-terms"]').trigger('click');

                    } else {
                        /*$status.html(data.message);*/
                    }
                    $('#container-async-news .pagination .prev').parent().addClass('page-prev');
                    $('#container-async-news .pagination .next').parent().addClass('page-next');

                    /*  console.log(data);
                    console.log(textStatus);
                    console.log(XMLHttpRequest);*/
                },
                error: function (MLHttpRequest, textStatus, errorThrown) {

                    /* $status.html(textStatus);

                    console.log(MLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);*/
                },
                complete: function (data, textStatus) {



                    msg = textStatus;

                    if (textStatus === 'success') {
                        msg = data.responseJSON.message;
                    }

                    $status.html(msg);

                    /*console.log(data);
                    console.log(textStatus);*/
                }
            });
        }



        /* Range */

        var currentDate = new Date();

        // Initializing slider
        $("#range").slider({
            min: 2017,
            max: currentDate.getFullYear() + 1,
            range: true,
            values: [2017, currentDate.getFullYear() + 1],
            change: function (event, ui) {}
        });

        $('#range span:nth-child(2)').append('<span class="minimumspan"></span>');
        $('#range span:nth-child(3)').append('<span class="maximumspan"></span>');

        /**
         * Bind get_posts to tag cloud and navigation
         */

        $('#container-async-news').on('click slidechange change keyup input', 'a[data-filter], .pagination a, #range, input.spub', function (event, ui) {
            if(event.preventDefault) { event.preventDefault(); }

            $this = $(this);


            $textpub = $('#container-async-news input.spub').val();
            var ppp = $('#container-async-news').data('ppp'),
                postypes = $('#container-async-news').data('postypes'),
                pagin = $('#container-async-news').data('pagin'),
                slider = $('#range').slider("instance");

                if ($("#range").length) {
                  var min = slider.options.values[0],
                      max = slider.options.values[1];
                }else{
                  var min = 1900,
                      max = currentDate.getFullYear() + 1;
                }

            $('.minimumspan').html(min);
            $('.maximumspan').html(max);





            /**
             * Set filter active
             */
            if ($this.data('filter')) {
                $page = 1;

                /**
                 * If all terms, then deactivate all other
                 */
                if ($this.data('term') === 'all-terms') {
                    $this.closest('ul').find('.active').removeClass('active');
                }
                else {
                    $this.closest('ul').find('a[data-term="all-terms"]').parent('li').removeClass('active');
                }

                /* If all select */


                if ($this.data('term') === 'allfilterselect') {
                    $this.closest('ul').find('.active').removeClass('active');
                }


                // Toggle current active
                if ($this.parents('.select-filter') && !$this.parent('li').hasClass("active") || $this.parents('.radio-filter') && !$this.parent('li').hasClass("active") ) {
                    $this.closest('.select-filter, .radio-filter').find('.active').removeClass('active');
                    $this.parent('li').toggleClass('active');
                }else{
                    $this.parent('li').toggleClass('active');
                }


                /**
                 * Get All Active Terms
                 */
                $active = {};
                $nom = [];
                $min = '';
                $ppp = '';
                $pagin = '';
                $postypes = [];

                if ($this.data('term') === 'all-terms') {
                    $terms  = $('.filtres-filters').find('.alltax.active');
                }else{
                    $terms  = $('.filtres-filters').find('.active:not(.alltax)');
                }


                if ($terms.length) {
                    $.each($terms, function(index, term) {

                        $a    = $(term).find('a');
                        $tax  = $a.data('filter');
                        $slug = $a.data('term');
                        $name = $a.data('name');

                        if ($tax in $active) {
                            $active[$tax].push($slug);
                        }
                        else {
                            $active[$tax] = [];
                            $active[$tax].push($slug);
                        }

                        if ($tax in $active) {
                            $nom.push($name);
                        }else{
                            $nom = ['test'];
                        }

                        if (typeof $nom == 'undefined' || $nom == '') {
                            $('.reset-selects-filter').css('display','none');
                        }else{
                            var str = '<ul class="list-unstyled">'
                            $nom.forEach(function(slide) {
                                if (slide) {
                                    str += '<li>'+ slide + '</li>';
                                }
                            });
                            str += '</ul>';
                            if ( $( "#filters-selects" ).length ) {
                                document.getElementById("filters-selects").innerHTML = str;
                            }
                            $('.reset-selects-filter').css('display','block');
                        }

                    });
                }

            }
            else {
                /**
                 * Pagination
                 */
                var attr = $(this).attr('href');

                if (typeof attr !== typeof undefined && attr !== false) {
                    $page = parseInt($this.attr('href').replace(/\D/g, ''));
                }

                $this = $('.nav-filter .active a');
            }


            $params    = {
                'page'  : $page,
                'terms' : $active,
                'name' : $nom,
                'search' : $textpub,
                'qty'   : $this.closest('#container-async-news').data('paged'),
                'postperpage' : ppp,
                'typepagin' : pagin,
                'poststypes' : postypes,
                'minimum': min,
                'maximum': max
            };


            // Run query
            get_posts($params);
        });

        /**
         * Show all posts on page load
         */
        $('a[data-term="all-terms"]').trigger('click');


        /* -- On click select -- */

        $(document).on('click', '.select-filter', function () {
            $(this).toggleClass('display');
        });


        $(document).on('click', '.select-filter a', function () {
            var value = $(this).text();
            $(this).parents('.select-filter').find('.select-all').text(value);
        });


        /* On click reset all */

        $(document).on('click', '#reset-filter a', function () {
            $('.filtres-filters li').removeClass('active');
            /*$('.select-all').text('Choisissez...');*/
        });



        $(document).on('click', '#container-async-news .pagination li > *', function () {
            $('html, body').animate({scrollTop:0},'100');
        });

    });

})(jQuery);
