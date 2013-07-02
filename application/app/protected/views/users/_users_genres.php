<? foreach($users as $k => $o) { ?>
    <div class="genrebar">
        <?=$o["genre"];?>
    </div>
    <div class="artists">
        <? foreach($o["users"] as $user) {
            $share_data = Users::getShareData($user);
            $user->soundcloud_data = json_decode($user->soundcloud_data);
            $user->track_data = json_decode($user->track_data);
            $this->renderPartial("_user", array("user" => $user, "share_data" => $share_data));
        } ?>
        <div class="clear"></div>
    </div>
<? } ?>
<div class="clear"></div>