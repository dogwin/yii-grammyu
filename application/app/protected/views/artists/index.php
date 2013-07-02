<div class="twelve columns heading">
    <h1><img src="<?=Yii::app()->theme->baseUrl;?>/img/artists_amplify_title.png" /></h1>
    <p>Click a track to listen, or click on a photo to find out more about the artist. 
        You can sort by genre, currently trending tracks, and most recently added. 
        If you like what you hear, Amplify it by sharing with your social networks!</p>

    <form id="search">

        <div class="column">
            <select id="genre">
                <option value="">All Categories</option>
                <? foreach($genres as $v): $text = $v; ?>
                <option value="<?=$v;?>"><?=$text;?></option>
                <? endforeach; ?>
            </select>
        </div>

        <div class="columns">
            <ul id="sort_menu">
                <li class="all-tracks checked">
                    <input type="radio" name="sort" value="all" checked />
                    All Tracks
                </li>
                <li class="trending">
                    <input type="radio" name="sort" value="trending" />
                    Trending
                </li>
                <li class="newest">
                    <input type="radio" name="sort" value="new" />
                    Newest
                </li>
            </ul>
        </div>

        <div class="columns">
            <input id="keyword" />
        </div>

        <div class="clear"></div>
    </form>

    <!--<div id="sort_tags">
        <span class="genre"></span>
        <span class="query"></span>
    </div>-->
</div>
<div id="ticker">
    <ul>
        <? foreach($ticker as $item) { ?>
        <li><img src="<?=Yii::app()->theme->baseUrl;?>/img/ticker_icon.png"><span class="username"><?=$item["artist"];?></span> just uploaded a track <span class="bitly"><a href="<?=$item["bitly"];?>"><?=$item["bitly"];?></a></span> - <span class="time"><?=$item["time"];?></span></li>
        <? } ?>
    </ul>
</div>
<div id="artists"></div>
<div class="clear"></div>
