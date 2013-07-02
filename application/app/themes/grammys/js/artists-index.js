$(function(){

    $("form").keypress(function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#search #keyword").keyup(function() {
        if($("#search #keyword").val().length >= 3){
            delay(function(){
                searchArtists();
                showSearchTags();
            }, 250 );
        }
    });

    $("#search #keyword").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13) { //Enter keycode
            searchArtists();
        }
    });

    $("#search #genre").change(function() {
        showSearchTags();
        searchArtists();
    });

    $(".loadmore").live("click", function(){
        loadMore();
    });

    searchArtists();
    showSearchTags();

    $(".loadmore").live('inview', function(event, isInView){
        if(isInView){
            loadMore();
        }
    });

    $("#keyword").each(function(){
        if (!$(this).data('originalValue')) {
            $(this).data('originalValue', $(this).val());
        }
    });

    $('#keyword').focus(function() {
        if ($(this).val() == $(this).data('originalValue')) {
            $(this).val('');
            $(this).addClass("focus");
        }
    }).blur(function(){
        if ($(this).val() == '') {
            $(this).val($(this).data('originalValue'));
            $(this).removeClass("focus");
        }
    });

    $("#genre").uniform();

    $('#sort_menu').find('li').on('click', function(e) {setSortVal(e)});

    $("#ticker ul").cycle({
        pause: true
    });

});


function scrolled(e) {
}

function loadMore(){
    $(".loadmore").remove();
    resultsPage += 1;
    $.ajax({
        type: "post",
        url: "/app/users/search",
        data: {
            keyword: $("#keyword").val(),
            genre: $("#genre").val(),
            sort: getSortVal(),
            page: resultsPage
        }
    }).done(function(response){
        $("#artists").append(response);
        initializePlayers();
    });
}