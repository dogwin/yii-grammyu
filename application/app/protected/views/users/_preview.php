<div class="artist">
    <div class='wrapper'>
        <div class='image'>
            <img src="<?=$user->profile_image;?>" class="profile" />
            <div class="upload"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_upload.png" /></div>
        </div>
        <div class='points'>
            <ul>
                <li><img src='<?=Yii::app()->theme->baseUrl;?>/img/amp_left.png' height='15' width='11'></li>
                <li>
                    <span class='count'>
                        <? if(isset($user->points)){ echo $user->points; } else { echo "0"; } ?>
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
            <a class='facebook'><img width='125' src='<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_facebook_crop.png'></a>
            <a class='twitter'><img width='125' src='<?=Yii::app()->theme->baseUrl;?>/img/button_amplify_twitter_crop.png'></a>

        </div>

        <? if($user->soundcloud_data->city): ?>
        <div class='location'>
            <img src="<?=Yii::app()->theme->baseUrl;?>/img/pin_2x.png" height='11' width='6'><span class="city"><?=$user->soundcloud_data->city;?></span>
        </div>
        <? endif; ?>
        <div class='genre'>
            <? if(isset($user->track_genre)){ echo $user->track_genre; } else { echo "Genre"; } ?>
        </div>
    </div>
</div>