(function ($) {
    $(function () {

        $('.button-collapse').sideNav( {
            edge: 'right'
        });
        $('select').material_select();
        $('.modal-trigger').leanModal();

    });     // end of document ready
})(jQuery); // end of jQuery name space

$(function () {
    var win_h = $(window).height();
    var win_w = $(window).width();
    var mytime;

    /* hover effect for top nav */
    $("#js-f-menu-toggle>li ").on("mouseenter", function (event) {
        $(".sub-menu").hide();
        $("#js-f-menu-toggle>li").children("a").removeClass("on");
        clearTimeout(mytime);
        $(this).children(".sub-menu").show();
        $(this).children("a").addClass("on");
    });

    $("#js-f-menu-toggle>li").on("mouseleave", function () {
        mytime = setTimeout("$('.sub-menu').hide()", 500);
    });

    /* click effect for mobile version*/
    $(" #slide-out>li").on("click", function (event) {
        $(".sub-menu").hide();
        $(this).find(".sub-menu").show();
        $("#slide-out>li").children("a").removeClass("on");
       // $(this).children(".sub-menu").show();
        $(this).children("a").addClass("on");
    });


    /* left-slider-wapper */
    if(win_w < 900){
        $(".f-page").removeClass("toggle");
        $(".f-left-slider-wapper-logo,.f-left-slider-wapper-toggle").hide();
    }else {
        $(".f-left-slider-wapper-toggle").css({"bottom":0});
    }
    $(window).resize(function() {
        win_h = $(window).height();
        win_w = $(window).width();
        $(".f-left-slider-wapper-fixed").css("height", win_h-60);
        $(".f-page").removeClass("toggle");
        if(win_w > 900){
            $(".f-page").removeClass("toggle01").addClass("toggle");
            if($(".f-page").hasClass("toggle")){
                $("#js-f-slider-wapper-show").hide();
                $(".f-page").removeAttr("style");
                $(".f-left-slider-wapper-toggle").removeAttr("style").css({"bottom":0});
                $(".f-left-slider-wapper-toggle,.f-left-slider-wapper-logo").show();
            }else{
                $("#js-f-slider-wapper-show").show();
                $(".f-left-slider-wapper-toggle,.f-left-slider-wapper-logo").hide();
            }
        }
        else {
            if(!$(".f-page").hasClass("toggle01")){
                $("#js-f-slider-wapper-show").show();
                $(".f-page").removeClass("toggle");
                $(".f-left-slider-wapper-toggle,.f-left-slider-wapper-logo").hide();
            }
        }
    });

    $("#js-f-slider-waper-hide").on("click", function () {
        $("#js-f-slider-wapper-show").show();
        $(".f-page").css("padding-left", "0");
        $(".f-page").removeClass("toggle");
        $(".f-page").removeClass("toggle01");

        if($(".f-page").hasClass("toggle")){

        }else{
            $(".f-left-slider-wapper-toggle,.f-left-slider-wapper-logo").hide();

        }
    });

    $("#js-f-slider-wapper-show").on("click", function () {
        $("#js-f-slider-wapper-show").hide();
        $(".f-left-slider-wapper-bloc").show();
        $(".f-page").removeAttr("style");
        $(".f-left-slider-wapper-toggle,.f-left-slider-wapper-logo").show();
        if(win_w > 900){
            $(".f-page").addClass("toggle").removeClass("toggle01");
        }else {
            $(".f-page").removeClass("toggle").addClass("toggle01");
            $(".f-left-slider-wapper-toggle").removeAttr("style").css({"top":(win_h-67)});
        }
    });

    /*polls section*/

    $('body').on('click',function (e) {
        $('#js-f-polls img').hide();
    });

    $('#js-f-polls span').on('click',function (e) {
        $('#js-f-polls img').hide();
        $(this).siblings('img').show();
        e.stopPropagation();
    });
});
    
var swiper = new Swiper('.swiper-container', {
    autoplay: 5000,
    pagination: '.swiper-pagination',
    paginationClickable: true
});