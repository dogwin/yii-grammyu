$(function(){

    getTracks();

    $(".image .upload").click(function(){
        $.fancybox({
            href: "#image",
            autoSize: false,
            width: 600,
            height: 460,
            beforeLoad: setupUploader,
            padding: 0,
            scrolling: false,
            fitToView: false
        });
    });

    $(".reset").click(setupUploader);
    $(".save").click(saveImage);
    $(".submit").click(validateForm);

    $("input[name=selected_track_id]").click(function(){
        if($(this).is(":checked")){
            var trackId = $(this).val();
            switchTrack(trackId);
        }
    });

    $("input[name=selected_track_id]").uniform();

    $("#genre").uniform();

    initializePlayers();

});

function showSearchTags() {
    var genre = $('#genre').val() || 'All Genres',
        query = ($('#keyword').val() === 'Search') ? '' : $('#keyword').val();

    //$('#sort_tags').find('.genre').html(genre);
    // $('#sort_tags').find('.query').html(query);
    // ˆ adds search query to the tag heading
}

function getTracks(){
    $.ajax({
        type: "get",
        url: "/app/users/tracks"
    }).done(function(msg){
       tracks = $.parseJSON(msg);
    });
}

function switchTrack(id){
    var track;
    $.each(tracks, function(k,v){
        if(v.id == id){
            track = v;
        }
    });
    if(track){
        buildPlayer(track);
        $("#confirmSelection .selection").html(track.title);
    }
}

function buildPlayer(track){
    $(".preview .player").empty();
    $(".preview .track .title").html(track.title);
    $(".preview .track .genre").html(track.genre);
    jQuery('<div/>',{
        id: "player_container_" + track.id,
        'class': "player_container"
    }).appendTo(".preview .player");
    jQuery('<audio/>',{
        id: "player_" + track.id,
        src: track.stream_url + "?consumer_key=" + clientId,
        type: "audio/mp3",
        controls: "controls",
        preload: "false",
        'class': "new"
    }).appendTo(".preview .player div");
    jQuery('<input/>',{
        id: "waveform_" + track.id,
        value: "/media/tracks/" + track.id + "/waveform_small.png",
        type: "hidden",
        'class': "waveform"
    }).appendTo(".preview .player div");
    initializePlayers();

}


function setupUploader(){
    swfobject.removeSWF("SWFUpload");
    $("#uploader").empty();
    $("#editor").hide();
    $("#uploader-queue").remove();
    $("#editor .image").empty();
    $("#editor .button").addClass("hidden");
    $('#uploader').uploadify({
        'formData'     : {
            'timestamp' : upload_timestamp,
            'token'     : upload_token,
            'userId'    : userId
        },
        'hideButton' : true,
        'width' : 150,
        'height' : 35,
        'fileSizeLimit' : '10MB',
        'multi' : false,
        'fileTypeDesc' : 'Image Files',
        'fileTypeExts' : '*.jpg; *.jpeg; *.gif; *.png',
        'swf' : themePath + '/plugins/uploadify/uploadify.swf',
        'uploader' : '/app/users/upload',
        'uploadLimit' : 1,
        'removeTimeout': 1,
        'onUploadSuccess' : function(file, data){
            $("#uploader").uploadify("disable", true);
            setTimeout(function(){
                setupEditor(data);
            },1500);
        },
        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
            alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        }
    });
}

function setupEditor(data){
    swfobject.removeSWF("SWFUpload");
    $("#uploader").empty();
    $("#uploader").hide();
    $("#uploader-queue").remove();
    $("#editor .image").empty();
    $("#editor .save, #editor .reset").removeClass("hidden");
    var imgData = $.parseJSON(data);
    $("#editor").height(0);
    $("#editor .image").html("<img src='/uploads/" + userId + "/source.jpg?" + Math.floor(Math.random() * 1000) + 2 + "' />");
    $("#editor .image img").Jcrop({
        onSelect: updateCoords,
        onChange: updateCoords,
        aspectRatio: imgData.ar,
        setSelect : [300,200,100,100],
        minSize: [150,100],
        onRelease: cropRelease
    });
    $("#editor").hide().height('auto').fadeIn(2000);

    $("#coords .original_w").val(imgData.width);
    $("#coords .original_h").val(imgData.height);

}


function cropRelease(c){
    $("#coords .x").val('');
    $("#coords .y").val('');
    $("#coords .x2").val('');
    $("#coords .y2").val('');
    $("#coords .w").val('');
    $("#coords .h").val('');
    updateAdjustedSizes();}

function updateCoords(c){
    $("#coords .x").val(c.x);
    $("#coords .y").val(c.y);
    $("#coords .x2").val(c.x2);
    $("#coords .y2").val(c.y2);
    $("#coords .w").val(c.w);
    $("#coords .h").val(c.h);
    updateAdjustedSizes();
}

function updateAdjustedSizes(){
    $("#coords .original_adjusted_w").val($("#editor .image img").width());
    $("#coords .original_adjusted_h").val($("#editor .image img").height());
}

function saveImage(){
    updateAdjustedSizes();
    $.ajax({
        type: "POST",
        url: "/app/users/crop",
        data: $("form").serialize()
    }).done(function(msg){
        $.fancybox.close();
        updateProfileImage();
    });
}

function updateProfileImage(){
    $.ajax({
        type: "POST",
        url: "/app/users/image"
    }).done(function(msg){
        $(".artist .image img.profile").attr("src",msg + '?' + (new Date).getTime());
    });
}


function validateFacebook(input){
    if (/^(https?:\/\/)?((w{3}\.)?)facebook.com\/.*/i.test(input)){
        return true;
    } else {
        return false;
    }
}

function validateTwitter(input){
    if (/^(https?:\/\/)?((w{3}\.)?)twitter\.com\/(#!\/)?[a-z0-9_]+$/i.test(input)){
        return true;
    } else {
        return false;
    }
}

function validateForm(){

    clearErrors("facebook");
    if($(".facebook input").val() != ''){
        if(!validateFacebook($(".facebook input").val())){
            $(".facebook input").addClass("error");
            $(".facebook small.error").show();
            return false;
        }
    }

    clearErrors("twitter");
    if($(".twitter input").val() != ''){
        if(!validateTwitter($(".twitter input").val())){
            $(".twitter input").addClass("error");
            $(".twitter small.error").show();
            return false;
        }
    }

    if($("input[name=selected_track_id]").length){
        if($("input[name=selected_track_id]").is(":checked")){
            showConfirmSelection();
            return false;
        } else {
            showNoTrackSelected();
            return false;
        }
    } else {
        if(trackId == ''){
            showNoTracksAvailable();
            return false;
        }
    }

    $("form").submit();



}

function showNoTracksAvailable(){
    popErrorModal("noTracksAvailable");
    return false;
}

function showNoTrackSelected(){
    popErrorModal("noTrackSelected");
    return false;
}

function showConfirmSelection(){
    popModal("confirmSelection");
    $("#confirmSelection .error").empty();
    $("#confirmSelection .submit").unbind().click(function(){
        if($("#agree").is(":checked")){
            $("form").submit();
        } else {
            $("#confirmSelection .error").html("You must agree to the terms and conditions before continuing.").show();
        }
    });
    $("#confirmSelection .cancel").live("click",function(){
        $.fancybox.close();
    });

}

function popModal(modalId){
    $.fancybox({
        href: "#" + modalId,
        autoSize: false,
        width: 778,
        height: 483,
        scrolling: false,
        padding: 0,
        fitToView: false
    });
}

function popErrorModal(modalId){
    $.fancybox({
        href: "#" + modalId,
        autoSize: false,
        width: 778,
        height: 283,
        scrolling: false,
        padding: 0,
        fitToView: false
    });
}

function clearErrors(selector){
    $("." + selector + " input").removeClass("error");
    $("." + selector + " .error").hide();
}
