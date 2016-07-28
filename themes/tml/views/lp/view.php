<div class="row-fluid" style="background-color:<?php echo $model->background_color; ?>; color:<?php echo $model->default_color; ?>;">
	<div class="headline row-fluid text-center" style="background-color:<?php echo $model->highlight_color; ?>; color:<?php echo $model->background_color; ?>; background-image: url('<?php echo $headlineImage; ?>');">
		<h1 class="span12">
			<?php echo $model->headline; ?>
		</h1>
	</div>
	<div class="formline row-fluid text-center" style="color:<?php echo $model->default_color; ?>; background-image: url('<?php echo $backgroundImage; ?>'); background-size: 100% auto;">
		<h2 class="byline span6 text-center" style="color:<?php echo $model->highlight_color; ?>;">
			<p><?php echo $model->byline; ?></p>
			<p><img class="hidden-phone" src="<?php echo $bylineImage; ?>" /></p>
		</h2>
		<div class="byline span6 text-left" style="color:<?php echo $model->highlight_color; ?>;">
			<form class="well text-left formstyle" style="opacity: 0.7;" method='POST'>
				<fieldset style="color:<?php echo $model->default_color; ?>; opacity: 1;">
					<?php if($status=='thankyou'){ ?>
					<h4><?php echo $model->thankyou_msg; ?></h4>
					<?php }else{ ?>
					<h4><?php echo $model->input_legend; ?></h4>
					<label><?php echo $model->input_label; ?></label>
					<input type="text" style="width:30px" name="Lp[prefix]">
					<input type="text" style="width:150px" name="Lp[number]">
					<span class="help-block" style="color:<?php echo $model->highlight_color; ?>;"><?php echo $model->input_eg; ?></span>
					<label><?php echo $model->select_label; ?></label>
					<select>
						<?php
						$options = explode('-', $model->select_options);
						foreach ($options as $option) {
							echo '<option value='.$option.'>'.$option.'</option>';
						}
						?>
					</select>
					<h5 style="color:<?php echo $model->highlight_color; ?>;"><?php echo $model->tyc_headline; ?></h5>
					<h6 style="color:black"><?php echo str_replace('$$mail$$', 'info@'.$_SERVER['HTTP_HOST'], $model->tyc_body); ?></h6>
					<h5 style="color:<?php echo $model->highlight_color; ?>;" class="checkbox">
						<input type="checkbox" name="Lp[tc]" /> <?php echo $model->checkbox_label; ?>
					</h5>
					<?php if($status=='validate'){ ?>
					<h6><?php echo $model->validate_msg; ?></h6>
					<?php } ?>
					<button type="submit" class="btn" ><?php echo $model->button_label; ?></button>
					<?php } ?>
				</fieldset>
			</form>
		</div>
	</div>
</div>
