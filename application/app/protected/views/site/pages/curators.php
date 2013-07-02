<div class="heading">
    <div class="title"><img src="<?=Yii::app()->theme->baseUrl;?>/img/curators_title.png" width="255" height="24" /></div>
    <div class="text">If your track is Amplified enough, you'll qualify for consideration by our Curators. If they like what they hear, you could have your sound shared with millions of fans.</div>
	<div class="totalreach">
	    <div class="socialreachtitle">AMPLIFIER SOCIAL REACH:</div><div class="numbers"><?=number_format($totalreach);?></div><div class="date"><?=$humanDate;?></div>
    </div>
   
</div>

<div class="curators">
    <div class="row">
        <? foreach($curators as $k => $curator): ?>
        <div class="curator four columns <?=$curator->slug;?>" >
            <a href="/app/curators/<?=$curator->slug;?>">
                <div class="tile" style="background-image: url(<?=Yii::app()->theme->baseUrl;?>/img/<?=$curator->image;?>); ">
                    <div class="title"></div>
                    <div class="reach">
    					<div class="count"><?=number_format($curator->reach);?></div>
                    </div>
                </div>
            </a>
        </div>
        <? endforeach; ?>
    </div>
</div>