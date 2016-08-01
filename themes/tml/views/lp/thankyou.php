<div class="row-fluid" style="background-color:<?php echo $model->background_color; ?>; color:<?php echo $model->default_color; ?>;">
	<div class="headline row-fluid text-center" style="background-color:<?php echo $model->highlight_color; ?>; color:<?php echo $model->background_color; ?>; background-image: url('<?php echo $headlineImage; ?>');">
		<h1 class="span12">
			<?php echo $model->thankyou_msg; ?>
		</h1>
	</div>
	<div class="container">
		<div class="formline row-fluids" style="color:<?php echo $model->default_color; ?>; background-image: url('<?php echo $backgroundImage; ?>'); background-size: 100% auto;">
		<?php
		$this->widget(
		    'bootstrap.widgets.TbThumbnails',
		    array(
		        'dataProvider' => $imgModel->search(),
		        'template' => "{items}\n{pager}",
		        'itemView' => '_thumb',
		    )
		);
		?>
		</div>
	</div>
</div>
