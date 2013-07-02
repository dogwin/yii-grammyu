<script type="text/javascript">
    var userId = '<?=$model->id?>';
    var soundcloudId = '<?=$model->soundcloud_id;?>';
    var trackId = '<?=$model->track_id;?>';
    var tracks = '';
</script>

<div class="twelve columns introduction">
    <h1><img src="<?=Yii::app()->theme->baseUrl;?>/img/setup_heading.png" width="250" /></h1>
    <div class="description"><p>Use the fields below to select your track from SoundCloud and enter your bio and links.</p></div>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'users-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array("enctype" => "multipart/form-data"),
)); ?>

<div class="form">

    <div class="tracks section">
        <div class="step">
            <img src="<?=Yii::app()->theme->baseUrl;?>/img/setup_step_1_2x_mobile.png" />
        </div>
        <div class="controls">
            <? if($model->track_id != ''){ ?>
            <div class="selected">
                <div class="text">You have selected:</div>
                <div class="title"><?=$model->track_title;?></div>
            </div>
            <? } else if(count($tracks)){ ?>
            <div class="items">
                <? foreach($tracks as $track): ?>
                <div class="item">
                    <input type="radio" name="selected_track_id" value="<?=$track->id;?>" <?=($model->track_id == $track->id) ? "checked" : "";?>  />
                    <span class="title"><?=$track->title;?></span>
                </div>
                <? endforeach; ?>
            </div>
            <? } else { ?>
            <div class="notracks warning">
                You currently have no tracks in your SoundCloud account, or they are set to Private. Please check to make sure your tracks are set to Public, and both "Widget enabled" and "Apps enabled" are checked for the track you wish to use on this site.
            </div>
            <? } ?>
        </div>
    </div>

    <div class="genre section">
        <div class="step">
            <img src="<?=Yii::app()->theme->baseUrl;?>/img/setup_step_2_2x_mobile.png" />
        </div>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'track_genre', $genres); ?>
        </div>
        <div class="clear"></div>
    </div>

    <div class="bio section">
        <div class="step">
            <img src="<?=Yii::app()->theme->baseUrl;?>/img/setup_step_3_2x_mobile.png" />
        </div>
        <div class="controls">
            <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        </div>
    </div>

    <div class="info section">
        <div class="step">
            <img src="<?=Yii::app()->theme->baseUrl;?>/img/setup_step_4_2x_mobile.png" />
        </div>
        <div class="controls">
            <div class="facebook">
                <?php echo $form->textField($model,'facebook',array('maxlength'=>200)); ?>
                <small class="error">Please enter a valid Facebook URL.</small>
            </div>
            <div class="twitter">
                <?php echo $form->textField($model,'twitter',array('maxlength'=>200)); ?>
                <small class="error">Please enter a valid Twitter URL.</small>
            </div>
        </div>
    </div>

    <div id="modals">
        <div id="image" class="modal">
            <div id="uploader"></div>
            <div id="editor">
                <div class="image"></div>
                <img class="save hidden" src="<?=Yii::app()->theme->baseUrl;?>/img/button_save_image.png" />
                <img class="reset hidden" src="<?=Yii::app()->theme->baseUrl;?>/img/button_reset_uploader.png" />
            </div>
        </div>

        <div id="confirmSelection" class="modal">
            <div class="heading"><img src="<?=Yii::app()->theme->baseUrl;?>/img/modal_heading_confirm.png" /></div>
            <div class="text">
                <p>Please confirm the track you want to use for this app is listed correctly below. This cannot be changed in the future so make sure it's the track you want to use!</p>
            </div>

            <div class="selection"></div>

            <div class="error"></div>
            <div class="terms">
                <input type="checkbox" id="agree" name="agree" /> I agree to the <a href="/app/rules" target="_blank">Official Rules</a> and <a href="/app/terms-and-conditions" target="_blank">Terms and Conditions</a>.
            </div>
            <div class="buttons">
                <img class="submit" src="<?=Yii::app()->theme->baseUrl;?>/img/button_submit.png" />
                <img class="cancel" src="<?=Yii::app()->theme->baseUrl;?>/img/button_cancel.png" />
            </div>
        </div>

        <div id="noTrackSelected" class="modal">
            <div class="heading"><img src="<?=Yii::app()->theme->baseUrl;?>/img/modal_heading_error.png" /></div>
            <div class="text">Please select a track before continuing. If the track you want to use is not listed here, please check that it exists in your SoundCloud account and that it is not set as a private track. Also make sure that both "Apps enabled" and "Widget enabled" are checked.</div>
        </div>

        <div id="noTracksAvailable" class="modal">
            <div class="heading"><img src="<?=Yii::app()->theme->baseUrl;?>/img/modal_heading_error.png" /></div>
            <div class="text">You currently have no tracks available from your SoundCloud account to choose from. If the track you want to use is not listed here, please check that it exists in your SoundCloud account and that it is not set as a private track. Also make sure that both "Apps enabled" and "Widget enabled" are checked.</div>
        </div>
    </div>



</div>

<div class="twelve columns footer">
    <div class="buttons">
        <img class="submit" src="<?=Yii::app()->theme->baseUrl;?>/img/setup_save_mobile.png" />
    </div>
</div>

<?php $this->endWidget(); ?>