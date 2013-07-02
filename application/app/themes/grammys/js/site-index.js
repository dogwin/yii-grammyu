$(function(){
    loadRandomArtists();
    setupVideoCarousel();
    stepsSlider();
    ticker();
    setupCarousels();
});

var currentpos = 0;
var totalVideos = 0;
var halfthumbwidth = 151/2;
var animationInProgress = false;

var videoThumbs = Array("pJgc27bgTZI.jpg","WK3JbBwHujw.jpg","FaSYu53txTA.jpg","axYKGyOzGqc.jpg","ari2n9hz_dY.jpg","FSQbAUvU8dA.jpg","B29MbKmfApA.jpg","GubBV1wXW_Y.jpg","D8TRSKnoPWY.jpg","DOvwjU5cBks.jpg","AkiCZp8TbWE.jpg","TgDthLay05s.jpg","MvEby9nHeaU.jpg","Fn-R9OaAAqE.jpg","TT6rQ8h2uIg.jpg","tYWr6qbuK1U.jpg","rVJuE537kjI.jpg","Wl6vVfhrpbs.jpg","185-pA1Z_i4.jpg","cGxp4V__8Og.jpg","6q7FEOPanSw.jpg","0jMGcm1yFu8.jpg","sDCJ8dscBBc.jpg");
var videoPaths = Array("http://www.youtube.com/watch?v=pJgc27bgTZI","http://www.youtube.com/watch?v=WK3JbBwHujw","http://www.youtube.com/watch?v=FaSYu53txTA","http://www.youtube.com/watch?v=axYKGyOzGqc","http://www.youtube.com/watch?v=ari2n9hz_dY","http://www.youtube.com/watch?v=FSQbAUvU8dA","http://www.youtube.com/watch?v=B29MbKmfApA","http://www.youtube.com/watch?v=GubBV1wXW_Y","http://www.youtube.com/watch?v=D8TRSKnoPWY","http://www.youtube.com/watch?v=DOvwjU5cBks","http://www.youtube.com/watch?v=AkiCZp8TbWE","http://www.youtube.com/watch?v=TgDthLay05s","http://www.youtube.com/watch?v=MvEby9nHeaU","http://www.youtube.com/watch?v=Fn-R9OaAAqE","http://www.youtube.com/watch?v=TT6rQ8h2uIg","http://www.youtube.com/watch?v=tYWr6qbuK1U","http://www.youtube.com/watch?v=rVJuE537kjI","http://www.youtube.com/watch?v=Wl6vVfhrpbs", "http://www.youtube.com/watch?v=185-pA1Z_i4","http://www.youtube.com/watch?v=cGxp4V__8Og","http://www.youtube.com/watch?v=6q7FEOPanSw","http://www.youtube.com/watch?v=0jMGcm1yFu8","http://www.youtube.com/watch?v=sDCJ8dscBBc");

function setupCarousels(){

    $(".browser").each(function(){
        var id = $(this).attr("id");
        $("#" + id + " .items").jCarouselLite({
            btnNext: "#" + id + " .next",
            btnPrev: "#" + id + " .prev"
        });
    });
}

function setupVideoCarousel(){
	totalVideos = videoThumbs.length;
	setBasePosition()
	checkCurrentPos();
}

function ticker(){
    $("#ticker ul").cycle({
        pause: true
    });
}

function setBasePosition(){
	var videoCenter = $(".videoCarousel").offset().left + 300;
	$("#vidA").offset({left:videoCenter - halfthumbwidth * 5 - 20});
	$("#vidB").offset({left:videoCenter - halfthumbwidth * 3 - 10});
	$("#vidC").offset({left:videoCenter - halfthumbwidth});
	$("#vidD").offset({left:videoCenter + halfthumbwidth * 1 + 10});
	$("#vidE").offset({left:videoCenter + halfthumbwidth * 3 + 20});
}


function video_rotate_left(){
	if ( animationInProgress == false){
		currentpos--;
		animateThumbs("left")
	}
}

function video_rotate_right(){
	if ( animationInProgress == false){
		currentpos++;
		animateThumbs("right")
	}
}

function returnCurrentOffset(incoming){
	if (incoming < 0){
		incoming += totalVideos;
	}
	var thisone = incoming % totalVideos;
	return(videoThumbs[thisone]);
}


function checkCurrentPos(){

	setBasePosition();

	if (currentpos > totalVideos-1){
		currentpos = 0;
	}
	if (currentpos < 0){
		currentpos = totalVideos-1;
	}
	$("#vidA").html("<img src=\"/media/videos/" + returnCurrentOffset(currentpos-2) + "\">");
	$("#vidB").html("<img src=\"/media/videos/" + returnCurrentOffset(currentpos-1)+ "\">");
	$("#vidC").html("<img src=\"/media/videos/" + returnCurrentOffset(currentpos-0) + "\">");
	$("#vidD").html("<img src=\"/media/videos/" + returnCurrentOffset(currentpos+1) + "\">");
	$("#vidE").html("<img src=\"/media/videos/" + returnCurrentOffset(currentpos+2) + "\">");
	
	animationInProgress = false;
	
}

function animateThumbs(whichway){
	
	animationInProgress = true;

	var distance
	if (whichway == "left"){
		distance = "+=165px";
	}else{
		distance = "-=165px";
	}
	var easemethod = "easeInOutCubic"
	var animduration = 200;
	
	$("#vidA").animate({left:distance}, { easing: easemethod, duration: animduration});
	$("#vidB").animate({left:distance}, { easing: easemethod, duration: animduration});
	$("#vidC").animate({left:distance}, { easing: easemethod, duration: animduration});
	$("#vidD").animate({left:distance}, { easing: easemethod, duration: animduration});
	$("#vidE").animate({left:distance}, { easing: easemethod, duration: animduration, complete:function(){checkCurrentPos()}});

}

function video_play(){

    $('audio').each(function(){this.player.pause()})
	
	$.fancybox({
		href: "#videoPlayer",
		autoSize: false,
		width: 800,
		height: 460,
		padding: 0,
		'scrolling' : 'no',
		fitToView: false
	});
	
	showFancyVideo();

}

function showFancyVideo(){
	
	var videoPath = videoPaths[currentpos];
	var thumbPath = videoThumbs[currentpos];

	jwplayer('JWvideoPlayer').setup({
    'flashplayer': '/themes/grammys/plugins/jwplayer/jwplayer.flash.swf',
    'id': 'playerID',
    'image': "/media/videos/" + thumbPath ,
    'width': '800',
    'height': '460',
    'file': videoPath,
    'autostart': 'true',
    'controlbar': 'bottom'
  });
}

function stepsSlider() {
	var STEPS_SLIDER = {
		$el: $(".steps-drag"),
		$slideTracker: $(".jscslider_tracker"),
		init: function() {
			STEPS_SLIDER .events();
		},
		events: function(){
			$("#stepsSlider").change(function() {
			    var sliderVal = $(this).val(),
			    		value     = sliderVal * 0.01;

			    STEPS_SLIDER.changeEventHandler(value, sliderVal);
			});
		},
		changeEventHandler: function(value, sliderVal) {
			var xpos = -(value * 808) * 2;

			STEPS_SLIDER.$el.css('left', xpos);

	  } 
	}

	STEPS_SLIDER.init();
}
