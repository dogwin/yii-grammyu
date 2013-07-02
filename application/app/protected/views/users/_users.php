<?
foreach($users as $user)
{
    $share_data = Users::getShareData($user);
    $user->soundcloud_data = json_decode($user->soundcloud_data);
    $user->track_data = json_decode($user->track_data);
    $this->renderPartial("_user", array("user" => $user, "share_data" => $share_data));
}
?>
<div class="clear"></div>
<div class="twelve columns">
    <? if($more) { ?><div class="loadmore"><img src="<?=Yii::app()->theme->baseUrl;?>/img/button_load_more.png" /></div><? } ?>
</div>