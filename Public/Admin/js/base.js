/*
 * 初始化窗口尺寸
 */
var autoSize = function(){
    //    alert($(".commonBtnArea").length);
    var webBodyWidth=getBodySize("w");
    var webBodyHight=getBodySize("h");
    var h=(webBodyHight-150);
    h=h<300?300:h;
    $('#control').css('height',h+'px');
    $('#Left').css('height',(h+12)+'px');
    var btns=$(".commonBtnArea").length;
    var rh=btns>0?h-50:h;
    $('#Right').css({
        height:rh+'px',
        width:(webBodyWidth-230)+'px'
    });
    if(btns>0){
        $(".commonBtnArea").css({
            width:(webBodyWidth-210-40-16)+'px'
        });
    }
    var c_s=0;
    $('#control').click(function(){
        if(c_s==1){
            if(btns>0){
                $(".commonBtnArea").animate({
                    width:(webBodyWidth-210-40-16)+'px'
                }, "fast");
            }
            $("#Right").animate({
                width: (webBodyWidth-230)+'px'
            }, "fast");
            $("#Left").animate({
                marginLeft:"0px"
            }, "fast");
            $(this).removeClass("close");
            c_s=0;
        }else{
            if(btns>0){
                $(".commonBtnArea").animate({
                    width: (webBodyWidth-66)+'px'
                }, "fast");
            }
            $("#Right").animate({
                width: (webBodyWidth-26)+'px'
            }, "fast");
            $("#Left").animate({
                marginLeft:"-197px"
            }, "fast");
            $(this).addClass("close");
            c_s=1;
        }
    });
}

/**
 * 选项卡
 */
$(function(){
    //初始化
    var tabs_content = $('.tabs li.active').attr('tabs-content');
    $("#"+tabs_content).removeClass('h');
    $('.tabs li').click(function(){
        tabs_content = $('.tabs li.active').attr('tabs-content');
        $("#"+tabs_content).addClass('h');
        $('.tabs li').removeClass('active');
        $(this).addClass('active');
        $("#"+$(this).attr('tabs-content')).removeClass('h');

    });
});