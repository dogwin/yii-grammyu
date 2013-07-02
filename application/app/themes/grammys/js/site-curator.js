$(function(){

	$.ajax({
		type:"POST",
		url:"/app/winners",
		data:{
			curatorID:curatorID
		}
		
	}).done(function(msg){
		$("#artists").html(msg);
		initializePlayers();
   		initializeShareLinks();
	});
	
	
});