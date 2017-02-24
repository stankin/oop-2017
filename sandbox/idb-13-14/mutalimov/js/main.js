/**
 * Created by ti on 18.02.17.
 */
$(document).ready(function(){
    // header
    $(".drop-down").hover(
        function(){
            $(".drop-menu-main-sub").css("display", "block");
        },
        function(){
            $(".drop-menu-main-sub").css("display", "none");
        }
   );
    $(".drop-menu-main-sub").hover(
        function(){
            $(".drop-menu-main-sub").css("display", "block");
        },
        function(){
            $(".drop-menu-main-sub").css("display", "none");
        }
    );
    //sidebar
    $(".service-menu").hover(
        function(){
            $(".service-menu").animate({width: "180"}, 100, function () {
                $(".service-menu-item span").css("display", "inline-block");
            });
        },
        function(){
            $(".service-menu").animate({width: "60"}, 100);
            $(".service-menu-item span").css("display", "none");
        }
    );
    $(".toggle-test .fa").click(
        function(){
            if ($(".toggle-test .fa").hasClass("fa-toggle-off")){
                $(this).removeClass("fa-toggle-off");
                $(this).addClass("fa-toggle-on");
                $(this).css("color", "#70c316")
            } else {
                $(this).addClass("fa-toggle-off");
                $(this).removeClass("fa-toggle-on");

            }
        }
    );
});