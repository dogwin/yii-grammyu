<!-- Share Data -->
<script>
    var share_data = <?=stripslashes(json_encode($share_data));?>;
</script>

<? if($model->id == Yii::app()->user->id){ ?>
<div class="twelve columns heading">
    <div class='header'>
        <img src='<?=Yii::app()->theme->baseUrl;?>/img/heading_share_your_sound_mobile.png' width='320' height='136'>
    </div>
    <div class="separator eight columns centered"></div>
    <div class='description ten columns centered'><span>Remember to share your track with your social networks to become eligible for consideration by our Amplifier Curators. You can share your track once per day.</span></div>
</div>
<? } ?>


<div class='profile'>
    <div class="twelve columns username">
        <h1><?=$model->username;?></h1>
    </div>
    <div class="twelve columns genre">
        <?=$model->track_genre;?>
    </div>
    <div class='twelve columns centered buttons'>
        <a class="amplify facebook"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_facebook.png" width="320" /></a>
        <a class="amplify twitter"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_twitter.png" width="320" /></a>
    </div>
    <div class="twelve columns image">
        <img src="<?=$model->profile_image;?>" class="image"/>
    </div>
    
     <div class="twelve columns titlePlayerBackground">
      <div class="twelve columns trackTitle">
			<h2><? if(strlen($model->track_title) > 30){
                        $model->track_title = substr($model->track_title,0,30) . "...";
                    };
                       echo $model->track_title;
            ?></h2>
	</div>
	<div class='mobilePlayer'>
		<div class='player'>
			<? if(isset($model->track_data->permalink_url)): ?>
			<div id="player_container_<?=$model->track_id;?>" class="player_container">
				<audio class="new" id="player_<?=$model->track_id;?>" src="<?=$model->track_data->stream_url;?>?consumer_key=<?=Yii::app()->params["soundcloud"]["clientId"];?>" type="audio/mp3" controls="controls" preload="false"></audio>
						<input type="hidden" id="waveform_black_<?=$model->track_id;?>" value="/media/tracks/<?=$model->track_id;?>/waveform_black_small.png" class="waveform" />
						<input type="hidden" id="waveform_grey_<?=$model->track_id;?>" value="/media/tracks/<?=$model->track_id;?>/waveform_grey_small.png" class="waveform" />
						<input type="hidden" id="waveform_gold_<?=$model->track_id;?>" value="/media/tracks/<?=$model->track_id;?>/waveform_gold_small.png" class="waveform" />
			</div>
			<? endif; ?>
		</div>
	</div>
    </div>
   
    <? if($model->description != ''){ ?>
    <div class='twelve columns bio'>
        <p><?=$model->description;?></p>
    </div>
    <? } ?>
    <? if($model->facebook != '' || $model->twitter != ''){ ?>
    <div class='info'>
        <ul>
            <? if($model->facebook != ''){ ?>
            <li><a target="_blank" href='<?=$model->facebook;?>'><img src="<?=Yii::app()->theme->baseUrl;?>/img/icon_facebook.png" width='27'></a><a target="_blank" href='<?=$model->facebook;?>'>Visit Facebook Page</a></li>
            <? } ?>
            <? if($model->twitter != ''){ ?>
            <li><a target="_blank" href='<?=$model->twitter;?>'><img src="<?=Yii::app()->theme->baseUrl;?>/img/icon_twitter.png" width='27'></a><a target="_blank" href='<?=$model->twitter;?>'>Visit Twitter Page</a></li>
            <? } ?>
            <li><a target="_blank" href='http://www.soundcloud.com/<?=$model->soundcloud_data->permalink;?>'><img src="<?=Yii::app()->theme->baseUrl;?>/img/icon_soundcloud.png" width='27'></a><a target="_blank" href='http://www.soundcloud.com/<?=$model->soundcloud_data->permalink;?>'>Visit SoundCloud Page</a></li>
        </ul>
    </div>
    <? } ?>
</div>

<div class="boxes">
    <div class="box">
        <div class="numbers" data-points="<?=$model->points;?>"></div>
        <div class="heading">Amplifications</div>
    </div>
    <div class="box">
        <div class="countdown"></div>
        <div class="heading">Time Left</div>
    </div>
</div>

<div class='badgeLeft'>
	<? if(1==1){ ?>
		<img src="<?=Yii::app()->theme->baseUrl;?>/img/badge_qualified.png" height='107' width='119'>
	<? } ?>
</div>
<div class='badgeRight'>
	<? if(1==1){ ?>
		<img src="<?=Yii::app()->theme->baseUrl;?>/img/badge_update.png" height='140' width='143'>
	<? } ?>
</div>
	
	