<div class="masthead content <?=$visited;?>">
    <div class="inner">
        <div class="description">Every legend starts somewhere. GRAMMY Amplifier gives you a chance to get your music in front of some of music's biggest icons. Share your sound here and your track could be heard by millions of fans worldwide. Check back soon for the big reveal of our Amplifier Curators!</div>
        <div class="explore"><a href="/app/connect"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_sign_up_now.png" width="240" height="33" /></a></div>

        <div class="videoCarousel">
            <div class="thumbsholder">
                <div class="vid" id="vidA"><img src="" width="151" height="86" alt="video"></div>
                <div class="vid" id="vidB"><img src="" width="151" height="86" alt="video"></div>
                <div class="vid" id="vidC"><img src="" width="151" height="86" alt="video"></div>
                <div class="vid" id="vidD"><img src="" width="151" height="86" alt="video"></div>
                <div class="vid" id="vidE"><img src="" width="151" height="86" alt="video"></div>
            </div>

            <div class="arrowControls">
                <div class="video_left"><a href="javascript:video_rotate_left()"><div class="video_left_arrow arrows">left arrow</div><img src="<?=Yii::app()->theme->baseUrl;?>/img/home_video_arrow_left.png" width="200" height="86" alt="arrow_left"></a></div>
                <div class="video_play"><a href="javascript:video_play()"><img src="<?=Yii::app()->theme->baseUrl;?>/img/home_video_play.png" height="86" alt="arrow_left"></a></div>
                <div class="video_right"><a href="javascript:video_rotate_right()"><div class="video_right_arrow arrows">right arrow</div><img src="<?=Yii::app()->theme->baseUrl;?>/img/home_video_arow_right.png" width="200" height="86" alt="arow_right"></a></div>
            </div>
        </div>

        <div id="video_modal">
            <div id="videoPlayer">
                <div id="JWvideoPlayer">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="curators content <?=$visited;?>">
    <div class="inner">
        <div class="heading">
            <div class="title"><img src="<?=Yii::app()->theme->baseUrl;?>/img/curators_title.png" width="255" height="24" /></div>
            <div class="text">If your track is Amplified enough, you'll qualify for consideration by our Curators. If they like what they hear, you could have your sound shared with millions of fans.</div>
        </div>
        <div class="soundcloudbug"><a href="http://www.soundcloud.com" target="_blank"><img src="<?=Yii::app()->theme->baseUrl;?>/img/home_soundcloud.png" /></a></div>
        <div class="totalreach">
            <div class="socialreachtitle">AMPLIFIER SOCIAL REACH:</div><div class="numbers"><?=number_format($totalreach);?></div><div class="date"><?=date("l M j, o");?></div>
        </div>
        <div class="row">
            <? foreach($curators as $k => $curator): ?>
            <div class="curator four columns" >
                <a href="/app/curators/<?=$curator->slug;?>">
                    <div class="tile" style="background-image: url(<?=Yii::app()->theme->baseUrl;?>/img/<?=$curator->image;?>); ">
                        <div class="title">
                        </div>
                        <div class="reach">
                            <div class="count"><?=number_format($curator->reach);?></div>
                        </div>
                    </div>
                </a>
            </div>
            <? endforeach; ?>
        </div>
        <div class="seemore"><a href="/app/curators">Go To Curators Page &gt; </a></div>
    </div>
</div>

<? if(!$visited){ ?>
<div class="about content">
    <div class="inner">
        <div class="heading">
            <div class="title"><img src="<?=Yii::app()->theme->baseUrl;?>/img/about_title.png" /></div>
            <div class="text">It's easy to get started. In three steps You could be discovered by one of our Amplifier Curators and have your track tweeted out to millions of fans. GET AMPLIFIED</div>
        </div>
        <div class="steps-points-wrap">
            <div id="slideWrap">
                <input type="number" min="0" max="100" value="0" class="slider" id="stepsSlider">
            </div>
            <div id="steps">
                <div class="steps-view-port">
                    <div class="steps-glow"></div>
                    <div class="steps-drag">
                        <div id="step1"><img src="<?=Yii::app()->theme->baseUrl;?>/img/about_step_1.png" /></div>
                        <div id="step2"><img src="<?=Yii::app()->theme->baseUrl;?>/img/about_step_2.png" /></div>
                        <div id="step3"><img src="<?=Yii::app()->theme->baseUrl;?>/img/about_step_3.png" /></div>
                    </div>
                </div>
            </div>
            <div class="getamped">
                <a href="/app/connect/"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_get_amped.png" /></a>
            </div>
        </div>
    </div>
</div>
<? } ?>

<? if(!$visited){ ?>
<div class="interstitial content"></div>
<? } ?>

<div class="amplifycontainer content <?=$visited;?>">
    <div class="inner">
        <div class="heading">
            <div class="text">Click a track to listen, or click on a photo to find out more about the artist. You can sort by genre, currently trending tracks, and most recently added. If you like what you hear, amplify it by sharing with your social networks!</div>
        </div>
        <div class="seemore"><a href="/app/artists"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_see_more.png" alt="See More"></a></div>
        <div id="ticker">
            <ul>
                <? foreach($ticker as $item) { ?>
                <li><img src="<?=Yii::app()->theme->baseUrl;?>/img/ticker_icon.png"><span class="username"><?=$item["artist"];?></span> just uploaded a track <span class="bitly"><a href="<?=$item["bitly"];?>"><?=$item["bitly"];?></a></span> - <span class="time"><?=$item["time"];?></span></li>
                <? } ?>
            </ul>
        </div>
        <div id="artists">

        </div>
    </div>
</div>
