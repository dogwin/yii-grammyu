<script>var curatorID="<?=$model->id;?>";</script>
<div class="curator-single <?=$model->slug;?>"></div>

<div class="reach">
	<ul>
		<li class="reach">Total Social Reach</li>
		<li class="stat"><?=number_format($model->reach);?></li>
		<li class="date"><?=date("l M j, o");?></li>
	</ul>
</div>

<div class="description">
	<p>
		<?=$model->description;?>
	</p>
	<div class="gradient"></div>
</div>

<div class="curator-title">
	<div class="amped-by">Amplified By</div>
	<div class="curator-name"><?=$model->title;?></div>
</div>

<div id="artists"></div>
