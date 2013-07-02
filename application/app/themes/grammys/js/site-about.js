
function getAmpedRollover(){
		$(".getampedHotspot").stop()
		$(".getampedHotspot").animate({opacity: 1  }, 500);
}

function getAmpedRollout(){
	$(".getampedHotspot").stop()
	$(".getampedHotspot").animate({opacity: 0  }, 500);
}

$(function(){
	var STEPS_SLIDER = {
		$el: $(".steps-drag"),
		$slideTracker: $(".jscslider_tracker"),
		init: function() {
			STEPS_SLIDER .events();
		},
		events: function(){
			$("#stepsSlider").change(function() {
			    var sliderVal   = $(this).val(),
			    		value       = sliderVal * 0.01;

			    STEPS_SLIDER.changeEventHandler(value, sliderVal);
			});
		},
		changeEventHandler: function(value, sliderVal) {
			var xpos = -(value * 808) * 2;

			STEPS_SLIDER.$el.css('left', xpos);

	  } 
	}

	STEPS_SLIDER.init();
});