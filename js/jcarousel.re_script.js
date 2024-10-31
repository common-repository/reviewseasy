(function($) {
    $(function() {
        
        $('#reviews-inner').on('jcarousel:reload jcarousel:create', function () {
                var carousel = $(this), width = carousel.innerWidth();
                if (width >= 600) { width = width / 3; }
                else if (width >= 400) { width = width / 2; }
                else { width = width / 1; }
                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
            }).jcarousel();

        var vw;
        if ($('.jcarousel-pagination').length > 0) {
                $('.jcarousel-pagination').each(function(){
                vw = ( ($('body').innerWidth() >= 600)  ? 3 : ( ( $('body').innerWidth() >= 400) ? 2 : 1 ) );                        
                var prnt = $(this).parent().attr('id');
                $(this).on('jcarouselpagination:active', 'a', function() {
                        $(this).addClass('active');
                    })
                    .on('jcarouselpagination:inactive', 'a', function() {
                        $(this).removeClass('active');
                    })
                    .on('click', function(e) {
                        e.preventDefault();
                    })
                    .jcarouselPagination({       
                        perPage:  vw,//( $(this).parent().attr('id') == "Clients" ) ? 3 :
                        item: function(page) {
                            return '<a href="#' + page + '">' + page + '</a>';
                        }
                    });
            });
            //jcarousel.reloadJcarousel();
            //jcarousel.reloadJcarouselPagination();
            //jcarousel.reloadJcarouselAutoscroll();
            //jcarousel.jcarouselAutoscroll({ autostart: true, target: '+='+(vw+1)});
        }
    });
})(jQuery);
