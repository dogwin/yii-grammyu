var resultsPage = 0;

$(function(){

    $(".connect.popup").popupWindow({
        centerBrowser: 1,
        width: 320
    });

    $("#header .toggle").click(function(){
        if($("#menu").hasClass("minimized")){
            showMenu();
        } else {
            hideMenu();
        }
    });

    $("#header .inner").hoverIntent(function(){
        showMenu();
    },function(){
        hideMenu();
    });

    $(".centerstagebutton").fancybox({
        href: "#exit_modal",
        autoSize: false,
        width: 529,
        height: 296,
        padding: 0,
        scrolling : 'no',
        fitToView: false
    });

});

function showMenu(){
  // $("#menu").stop().animate({ height: "40" }, 400, "easeOutBack").removeClass("minimized");
}

function hideMenu(){
  //  $("#menu").stop().animate({ height: "0" }, 400, "easeInBack").addClass("minimized");;
}
