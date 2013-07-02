$(function(){

	var current = "00:00:00";
    var montharray = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    var yr = 2013;
    var m = 2;
    var d = 10;
    var time;

    function calCountdown(){
    	time = setInterval(countdown,1000);
    }

    function countdown(){
        var today=new Date();
        var todayy=today.getYear();
        if (todayy < 1000)
            todayy+=1900
        var todaym=today.getMonth();
        var todayd=today.getDate();
        var todayh=today.getHours();
        var todaymin=today.getMinutes();
        var todaysec=today.getSeconds();
        var todaystring=montharray[todaym]+" "+todayd+", "+todayy+" "+todayh+":"+todaymin+":"+todaysec;
        futurestring=montharray[m-1]+" "+d+", "+yr;
        dd=Date.parse(futurestring)-Date.parse(todaystring);
        dday=Math.floor(dd/(60*60*1000*24)*1);
        dhour=Math.floor((dd%(60*60*1000*24))/(60*60*1000)*1);
        dmin=Math.floor(((dd%(60*60*1000*24))%(60*60*1000))/(60*1000)*1);
        dsec=Math.floor((((dd%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1);
        if(dday<=0&&dhour<=0&&dmin<=0&&dsec<=0){
             clearInterval(time);
             convertFonts(current,"small");
             return;
        } else {

        }

        if(dday.toString().length == 1)
            dday = '0'+dday;
        if(dhour.toString().length == 1)
            dhour = '0'+dhour;
        if(dmin.toString().length == 1)
            dmin = '0'+dmin;
        if(dsec.toString().length == 1)
            dsec = '0'+dsec;

        convertFonts(dday+ ":"+dhour+":"+dmin+":"+dsec, "small");
        
    }

    //year/month/day
    calCountdown();


    $(".bitly input[type=text]").focus(function(){
        var $this = $(this);
        $this.select();
        $this.mouseup(function() {
            $this.unbind("mouseup");
            return false;
        });
    });

    initializePlayers("wide");

    $(".amplify.facebook").click(function(){
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

    $(".amplify.twitter").popupWindow({
        windowURL: share_data.twitter.url,
        centerBrowser: 1,
        width: 400,
        height: 500
    });

    $(".amplify.twitter").click(function(){
        addTwitterCounter(share_data.track_id);
    });

    convertFonts($(".numbers").attr("data-points"), "regular");

});

function convertFonts(value, size){
    var size;
    var style;
    if(size == "small"){
        size = "SMALL";
        style = ".countdown";
    } else {
        size = '';
        style = ".numbers";
    }

   $(".countdown").empty();
    for (var i = 0; i < value.length; i++) {
        var currentChar = value.substr(i, 1);
        $(style).append(currentChar);
        
    };
}



