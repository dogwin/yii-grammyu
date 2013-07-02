<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div class="heading">
    <div class="title"><img src="<?=Yii::app()->theme->baseUrl;?>/img/site_error.png" width="256" height="115" /></div>
    <div class="text">Error <?php echo $code; ?></div>
    <div class="text"><?php echo CHtml::encode($message); ?></div>
</div>




