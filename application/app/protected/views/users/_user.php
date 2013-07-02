<div class="four columns artist" id="artist-<?=$user->id;?>">
    <div class='wrapper'>
        <div class='image'>
            <a href="/<?=$user->soundcloud_data->permalink;?>"><img src="<?=$user->profile_image;?>" /></a>
        </div>
         <div class='points'>
            <ul>
                <li><img src='<?=Yii::app()->theme->baseUrl;?>/img/amp_left.png' height='15' width='11'></li>
                <li>
                    <span class='count'>
                        <? if(isset($user->points)){ echo number_format($user->points); } else { echo "0"; } ?>
                    </span> AMPLIFIES
                </li>
                <li><img src='<?=Yii::app()->theme->baseUrl;?>/img/amp_right.png' height='15' width='11'></li>
            </ul>
        </div>
        <div class='username'><h2><?=$user->username;?></h2></div>
        <div class='track'>
            <div class='title'>
                <?
                if(isset($user->track_title)){
                    if(strlen($user->track_title) > 30){
                        $user->track_title = substr($user->track_title,0,30) . "...";
                    }
                    echo $user->track_title;
                } else {
                    echo "Title";
                } ?>
            </div>
        </div>
        <div class='player'>
            <? if(isset($user->track_data->permalink_url)): ?>
            <div id="player_container_<?=$user->track_id;?>" class="player_container">
                <audio class="new" id="player_<?=$user->track_id;?>" src="<?=$user->track_data->stream_url;?>?consumer_key=<?=Yii::app()->params["soundcloud"]["clientId"];?>" type="audio/mp3" controls="controls" preload="none"></audio>
                <input type="hidden" id="waveform_black_<?=$user->track_id;?>" value="/media/tracks/<?=$user->track_id;?>/waveform_black_small.png" class="waveform" />
                <input type="hidden" id="waveform_grey_<?=$user->track_id;?>" value="/media/tracks/<?=$user->track_id;?>/waveform_grey_small.png" class="waveform" />
                <input type="hidden" id="waveform_gold_<?=$user->track_id;?>" value="/media/tracks/<?=$user->track_id;?>/waveform_gold_small.png" class="waveform" />

            </div>
            <? endif; ?>
        </div>
        <div class='share'>
            <a class='amplify facebook'><img width='125' src='<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_facebook_crop.png'></a>
            <a class='amplify twitter' href="<?=$share_data["twitter"]["url"];?>"><img width='125' src='<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_twitter_crop.png'></a>
        </div>
        <div class='location'>
        <? if($user->soundcloud_data->city): ?>
            <img src="<?=Yii::app()->theme->baseUrl;?>/img/pin_2x.png" height='11' width='6'><span class="city"><?=$user->soundcloud_data->city;?></span>
        <? endif; ?>
        </div>
        <div class='genre'>
        	<? if(isset($user->track_genre)){ echo $user->track_genre; } ?>
        </div>
        <div class='badgeLeft'>
        	<? if($user->qualified){ ?>
        	 	<img src="<?=Yii::app()->theme->baseUrl;?>/img/badge_qualified.png" height='107' width='119'>
        	<? } ?>
        </div>
        <div class='badgeRight'>
        	<? if(1==0){ ?>
        	 	<img src="<?=Yii::app()->theme->baseUrl;?>/img/badge_clarkson.png" height='140' width='143'>
        	<? } ?>
        </div>
        
        
        
    </div>
</div>
<script>
    $("#artist-<?=$user->id;?>").data("share_data", <?=json_encode($share_data);?>);
</script>