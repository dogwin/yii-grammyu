<!-- Share Data -->
<script>
    var share_data = <?=stripslashes(json_encode($share_data));?>;
</script>

<? if($model->id == Yii::app()->user->id){ ?>

<div class="twelve columns heading">
    <div class='headerTall'>
        <img src='<?=Yii::app()->theme->baseUrl;?>/img/heading_share_your_sound_singleline.png' width='509' height='97'>
		<div class="separator eight columns centered"></div>
		<div class='description ten columns centered'><span>Remember to share your track with your social networks to become eligible for consideration by our Amplifier Curators. You can share your track once per day.</span></div>
		<div class='buttons'>
			<a class="amplify facebook"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_facebook.png" width='241' /></a>
			<a class="amplify twitter"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_twitter.png" width='241' ></a>
		</div>
	
		<div class="row bitly">
			<label>Short URL</label>
			<input type="text" value="<?=$model->bitly;?>" class="noentry" id="bitly" />
		</div>
    
    </div>
    
</div>

<? } else { ?>

<div class="twelve columns heading">

	<div class='headerShort'>
		<div class='buttons'>
			<a class="amplify facebook"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_facebook.png" width='241' /></a>
			<a class="amplify twitter"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_twitter.png" width='241' ></a>
		</div>
	
		<div class="row bitly">
			<label>Short URL</label>
			<input type="text" value="<?=$model->bitly;?>" class="noentry" id="bitly" />
		</div>
    </div>
</div>

<? } ?>


<div class="twelve columns artist">

    <div class='profile'>
        
        <div class='artistNameShort'><span><h1><?=$model->username;?></h1></span></div>
        <div class='details'>
			
            <div class='profileImage'><img src="<?=$model->profile_image;?>" class="image"/></div>
			<div class='imageOverlay'></div>
            <h2><? if(strlen($model->track_title) > 30){
                        $model->track_title = substr($model->track_title,0,30) . "...";
                    };
                       echo $model->track_title;
            ?></h2>

            <div class='player'>
                <? if(isset($model->track_data->permalink_url)): ?>
                <div id="player_container_<?=$model->track_id;?>" class="player_container">
                    <audio class="new" id="player_<?=$model->track_id;?>" src="<?=$model->track_data->stream_url;?>?consumer_key=<?=Yii::app()->params["soundcloud"]["clientId"];?>" type="audio/mp3" controls="controls" preload="false"></audio>
                    <input type="hidden" id="waveform_black_<?=$model->track_id;?>" value="/media/tracks/<?=$model->track_id;?>/waveform_black_large.png" class="waveform" />
                	<input type="hidden" id="waveform_grey_<?=$model->track_id;?>" value="/media/tracks/<?=$model->track_id;?>/waveform_grey_large.png" class="waveform" />
                	<input type="hidden" id="waveform_gold_<?=$model->track_id;?>" value="/media/tracks/<?=$model->track_id;?>/waveform_gold_large.png" class="waveform" />
                	
                </div>
                <? endif; ?>
            </div>

            <? if($model->description != ''){ ?>
            <div class='bio'>
                <p><?=$model->description;?></p>
            </div>
            <? } ?>
        </div>
        
    </div>

    <div class='stats'>
        <div class='boxes'>
            <div class='top'>
               
                 <div class="numbers data" data-points="<?=$model->points;?>"></div>
                 <div class="text">AMPLIFICATIONS</div>
                 <div class="gradient"></div>
            </div>
            <div class='bottom'>
                <div class="countdown data"></div>
                <div class="text">TIME LEFT</div>
                <div class="gradient"></div>
            </div>
            
         </div>
         
            <? if($model->facebook != '' || $model->twitter != ''){ ?>
            <div class='info'>
                <ul>
                    <? if($model->facebook != ''){ ?>
                    <li><a target="_blank" href='<?=$model->facebook;?>'><img src="<?=Yii::app()->theme->baseUrl;?>/img/icon_facebook.png" width='27'></a><a target="_blank" href='<?=$model->facebook;?>'>View Facebook Profile</a></li>
                    <? } ?>
                    <? if($model->twitter != ''){ ?>
                    <li><a target="_blank" href='<?=$model->twitter;?>'><img src="<?=Yii::app()->theme->baseUrl;?>/img/icon_twitter.png" width='27'></a><a target="_blank" href='<?=$model->twitter;?>'>View Twitter</a></li>
                    <? } ?>
                    <li><a target="_blank" href='http://www.soundcloud.com/<?=$model->soundcloud_data->permalink;?>'><img src="<?=Yii::app()->theme->baseUrl;?>/img/icon_soundcloud.png" width='27'></a><a target="_blank" href='http://www.soundcloud.com/<?=$model->soundcloud_data->permalink;?>'>View SoundCloud Page</a></li>
                </ul>
            </div>
            <? } ?>
            
       
    </div>
    
	<div class='badgeLeft'>
		<? if($model->qualified){ ?>
			<img src="<?=Yii::app()->theme->baseUrl;?>/img/badge_qualified.png" height='107' width='119'>
		<? } ?>
	</div>
	<div class='badgeRight'>
		<? if(1==1){ ?>
			<a href="/app/about"><img src="<?=Yii::app()->theme->baseUrl;?>/img/badge_update.png" height='140' width='143'></a>
		<? } ?>
	</div>


</div>

<div class="share-components">

</div>


        
        
<div class="clear"></div>