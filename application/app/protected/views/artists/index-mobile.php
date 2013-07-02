<div class="heading twelve columns">
    <div class="title"></div>
    <div class="text">Ready to amplify your musical dreams?<br/> GRAMMY Amplifier gives you a chance to be heard by some of the biggest names in music.</div>
</div>

<form id="search">
    <div class="six columns keyword">
        <input id="keyword" value="Search" />
    </div>

    <div class="six columns genre">
        <select id="genre">
            <option value="">Categories</option>
            <? foreach($genres as $v): $text = $v; ?>
            <option value="<?=$v;?>"><?=$text;?></option>
            <? endforeach; ?>
        </select>
    </div>

    <ul id="sort_menu" class="twelve columns">
        <li class="checked">
            <input type="radio" name="sort" value="all" checked />
            All Tracks
        </li>
        <li>
            <input type="radio" name="sort" value="trending" />
            Trending
        </li>
        <li>
            <input type="radio" name="sort" value="new" />
            Newest
        </li>
    </ul>

    <div class="clear"></div>
</form>

<div id="artists"></div>

