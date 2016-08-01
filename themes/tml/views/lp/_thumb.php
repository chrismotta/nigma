<?php $href = $this->createUrl('lp/bigImg', array('id'=> $data->id)) ?>
<li class="span3">
	<a href="<?php echo $href;?>" class="thumbnail thankyou-thumb" style="background-image:url(<?php echo $data->getImagePath($data->file_name);?>)">
	</a>
</li>