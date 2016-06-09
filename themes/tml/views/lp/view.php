<div class="headline row-fluid text-center" style="background-color:<?php echo $model->highlight_color; ?>; color:<?php echo $model->background_color; ?>">
	<h1 class="span12">
		<?php echo $model->headline; ?>
	</h1>
</div>
<div class="formline row-fluid text-center" style="background-color:<?php echo $model->background_color; ?>; color:<?php echo $model->default_color; ?>;">
	<h2 class="byline span6 text-center" style="color:<?php echo $model->highlight_color; ?>;">
		<?php echo $model->byline; ?>
	</h2>
	<div class="byline span6 text-left" style="color:<?php echo $model->highlight_color; ?>;">
		<form class="well text-left formstyle">
			<fieldset style="color:<?php echo $model->default_color; ?>;">
				<h4><?php echo $model->input_legend; ?></h4>
				<label><?php echo $model->input_label; ?></label>
				<input type="text" style="width:30px">
				<input type="text" style="width:150px">
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
				<h6 style="color:<?php echo $model->highlight_color; ?>;"><?php echo $model->tyc_body; ?></h6>
				<h5 style="color:<?php echo $model->highlight_color; ?>;" class="checkbox">
					<input type="checkbox" /> <?php echo $model->checkbox_label; ?>
				</h5>
				<button type="submit" class="btn" ><?php echo $model->button_label; ?></button>
			</fieldset>
		</form>
	</div>
</div>
