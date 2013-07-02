var xhr;

$(function(){
    $("#artists .amplify.facebook").live("click",function(){
        var artistId = $(this).parent().parent().parent().attr("id");
        var share_data = $("#" + artistId).data("share_data");
        FB.ui(
            {
                method: 'feed',
                name: share_data.facebook.name,
                link: share_data.facebook.link,
                picture: share_data.facebook.picture,
                caption: share_data.facebook.caption,
                description: share_data.facebook.description
            },
            function(response) {
                if (response && response.post_id) {
                    addFacebookCounter(share_data.track_id, response.post_id);
                }
            }
        );
    });

});

function initializeShareLinks(){
    $("#artists .amplify.twitter").popupWindow({
        centerBrowser: 1,
        width: 400,
        height: 500
    });

    $("#artists .amplify.twitter").click(function(){
        var artistId = $(this).parent().parent().parent().attr("id");
        var share_data = $("#" + artistId).data("share_data");
        addTwitterCounter(share_data.track_id);
    });

}

function searchArtists() {
    resultsPage = 0;
    if(xhr){ xhr.abort(); }
    xhr = $.ajax({
        type: "post",
        url: "/app/users/search",
        data: {
            keyword: $("#keyword").val(),
            genre: $("#genre").val(),
            sort: getSortVal(),
            page: resultsPage
        }
    }).done(function(response){
        $("#artists").html(response);
        initializePlayers();
        initializeShareLinks();
    });
}

function getAmplifiedAritsts(slug){

}

function showSearchTags() {
    var genre = $('#genre').val() || 'All Genres',
        query = ($('#keyword').val() === 'Search') ? '' : $('#keyword').val();

    //$('#sort_tags').find('.genre').html(genre);
    // $('#sort_tags').find('.query').html(query);
    // ˆ adds search query to the tag heading
}

function getSortVal() {
    var radios = document.getElementsByName('sort'),
        checked;
    for (var i=0; i<radios.length; i++) {
        if (radios[i].checked) {
            checked = radios[i].value; 
        }
    }
    return checked;
}

function setSortVal(e) {
    $('#sort_menu').find('li').removeClass('checked');
    $(e.currentTarget)
        .addClass('checked')
        .find('input').attr('checked', true);
    searchArtists();
}

function loadRandomArtists(){
    $.ajax({
        type: "post",
        url: "/app/users/random"
    }).done(function(response){
        $("#artists").html(response);
        initializePlayers();
        initializeShareLinks();
    });
}

function initializePlayers(size){
    var width = 298;
    if(size == "wide" && !$("body.phone").length){
        width = 535;
    }
    $('audio.new').mediaelementplayer({
        audioWidth: width,
        audioHeight: 58,
        startVolume: 1,
        features: ['playpause','progress'],
        success: function(mediaElement, domObject){
            var id = domObject.id.replace("player_","");
            $("audio.new").removeClass("new");
            setWaveform(id);
            mediaElement.addEventListener('play', function(){
                addPlayCounter(id);
            });
        }
    });
}

function setWaveform(id){
    $("#player_container_" + id + " .mejs-time-total").css({"background-image": 'url(' + $("#waveform_black_" + id).val() + ')'});
    $("#player_container_" + id + " .mejs-time-loaded").css({"background-image": 'url(' + $("#waveform_grey_" + id).val() + ')'});
    $("#player_container_" + id + " .mejs-time-current").css({"background-image": 'url(' + $("#waveform_gold_" + id).val() + ')'});
}

function addPlayCounter(id){
    $.ajax({
        type: 'POST',
        url: '/app/artists/points',
        data: {
            type: 'play',
            track_id: id,
            timestamp: timestamp,
            token: token
        }
    });
}

function addFacebookCounter(id, post_id){
    $.ajax({
        type: 'POST',
        url: '/app/artists/points',
        data: {
            type: 'facebook',
            track_id: id,
            post_id: post_id,
            timestamp: timestamp,
            token: token
        }
    });
}

function addTwitterCounter(id){
    $.ajax({
        type: 'POST',
        url: '/app/artists/points',
        data: {
            type: 'twitter',
            track_id: id,
            timestamp: timestamp,
            token: token
        }
    });
}

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();