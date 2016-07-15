<?php $href = $this->createUrl('update', array('id'=> $data->id)) ?>
<li class="span3">
	<div style="margin-bottom: 27px;">
		<code style="float:left; padding: 0px 5px; 0px 5px;">
			<a href="<?php echo $href;?>">
				ID: <?php echo $data->id;?> - Type: <?php echo $data->type;?>
			</a>
		</code>
		<code style="float:right; padding: 0px 5px; 0px 5px;">
		<?php 
		echo CHtml::link("Delete", '#', array(
		'submit'=>array('delete', "id"=>$data->id), 'confirm' => 'Are you sure you want to delete this image?'));
		?>
		</code>
	</div>
	<a href="<?php echo $href;?>" class="thumbnail" style="clear:both;">
		<img src="<?php echo $data->getImagePath($data->file_name);?>" alt="">
	</a>
</li>