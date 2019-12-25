/*-------------- DOCUMENT.READY START ---------------------*/

jQuery(document).ready(function(){
/**------------flicker start-----------*/

(function($){

    $('.flickr-slick-feeds').each(function()
    {
        var fslidenumber = $(this).data('item');
        $(this).slick({
          slidesToShow: fslidenumber,
          autoplay: true,
          autoplaySpeed: 2000,
        });
    });
/**-----------------Twitter Slider----------------------*/

$('.twitter-slick-feeds').each(function()
{
    var slidenumber = $(this).data('item');
   $(this).slick({
      slidesToShow: slidenumber,
      autoplay: true,
      autoplaySpeed: 2000,
      dots:false,
      arrows: false
    });
});

/**-----------------instagram Slider start----------------------*/

$('.instagram-slick-feeds').each(function()
{
    var slidenumber = $(this).data('item');
    // console.log(slidenumber);
   $(this).slick({
      slidesToShow: slidenumber,
      // slidesToShow: 1,      
      autoplay: true,
      autoplaySpeed: 2000,
      dots:true,
      arrows: false
    });
});
/**-----------------instagram Slider end----------------------*/
/*---------------------woocom prodoct slider -------------------------*/
$('.widget-product-slick-slider').each(function()
{  var slidenumber = $(this).data('item');
    var sliderpagination = $(this).data('slidepagenation');
    var dots=(sliderpagination=='dots'?true:false);
    var arrow=(sliderpagination=='arrow'?true:false);
    // console.log(sliderpagination);
   $(this).slick({
      slidesToShow: slidenumber,
      autoplay: true,
      autoplaySpeed: 2000,
      dots:dots,
      arrows: arrow
    });
});
/*---------------------woocom prodoct slider -------------------------*/
$('[data-toggle="popover-test"]').popover();
$('[data-toggle="tooltip"]').tooltip();
/****Tab js***/
var tabs = $('ul.tabs');
    tabs.each(function (i) {
        //Get all tabs
        var tab = $(this).find('> li > a');
        $("ul.tabs li:first").addClass("active").fadeIn(); //Activate first tab
        $("ul.tabs li:first a").addClass("active").fadeIn(); //Activate first tab
        $("ul.tabs-content li:first").addClass("active").fadeIn(); //Activate first tab
        tab.click(function (e) {
            //Get Location of tab's content
            var contentLocation = $(this).attr('href') + "Tab";
            //Let go if not a hashed one
            if (contentLocation.charAt(0) == "#") {
                e.preventDefault();
                //Make Tab Active
                tab.removeClass('active');
                $(this).addClass('active');
                //Show Tab Content & add active class
                $(contentLocation).show().addClass('active').siblings().hide().removeClass('active');
            }
        });
    });
/*******************Skill************************/
$('.skillbar').each(function () {
        $(this).find('.skillbar-bar').animate({
            width: $(this).attr('data-percent')
        }, 1500);
    });

    $(".skillbar-title em").html(function(index, old) {
        return old.replace(/(\b\w+)$/, '<span>$1</span>');
    });
/*----------------------------------------------------*/
    /*  CALENDAR
    /*----------------------------------------------------*/
    if($("#calendar").length)
        {
            $(function() {

                var cal = $( '#calendar' ).calendario( {
                        //caldata : codropsEvents,
                        weekabbrs : [ 'Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa' ],
                        displayWeekAbbr : true
                    } ),
                    //$day = $( '#custom-day' ).html( cal.getDayName() ),
                    $month = $( '#custom-month' ).html( cal.getMonthName() ),
                    $year = $( '#custom-year' ).html( cal.getYear() );
                $( '#next-date' ).on( 'click', function() {
                    cal.gotoNextMonth( updateMonthYear );
                } );
                $( '#prev-date' ).on( 'click', function() {
                    cal.gotoPreviousMonth( updateMonthYear );
                } );
                $( '#current-date' ).on( 'click', function() {
                    cal.gotoNow( updateMonthYear );
                } );

                function updateMonthYear() {
                    $month.html( cal.getMonthName() );
                    $year.html( cal.getYear() );
                }
                var today = new Date();
                $('#current-date').html(today.getDate());
                cal.setData( {
                                '06-14-2017' : '<a href="#">testing</a>',
                                '06-10-2017' : '<a href="#">testing</a>',
                                '06-16-2017' : '<a href="#">testing</a>'
                            } );
            });

            $("#calendar").on("click", ".fc-content", function(evt) {
                var imgSrc = $(this).find("img").attr("src");
                var heading = $(this).find("a").text();
                var anchor = $(this).find("a").attr("href");
                $("#updateArticle").children("img").attr("src", imgSrc).css("height","137px").end()
                                   .children("a").attr("href",anchor).text(heading);
                if(imgSrc==undefined){$("#updateArticle").children("img").remove(); }
            })
        }


})(jQuery);
/**------------flicker end-----------*/
/**------------Popular article start-----------*/
(function($){


$('.slick-popular-articles').each(function()
{
    // var slideno=$(this).data('item');
   $(this).slick({
      // slidesToShow: slideno,
      autoplay: true,
      autoplaySpeed: 2000,
      // vertical: true,
      arrows: false,
      dots: true
    });
});
$('.recent-post-layout-sliderlayout').each(function()
{
    // var slideno=$(this).data('item');
   $(this).slick({
      // slidesToShow: slideno,
      autoplay: true,
      autoplaySpeed: 2000,
      arrows: false,
      dots: true
    });
});

$(".post-fratured-gallery-slider").slick({
        dots: true,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false
      });


})(jQuery);
/**------------Popular article end-----------*/

jQuery(".category-widget .panel-default").each(function(){
			jQuery(this).find('.panel-collapse').css('height','0px');
		});


});/*-------------- DOCUMENT.READY ENDS ---------------------*/
