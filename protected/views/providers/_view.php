<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prefix')); ?>:</b>
	<?php echo CHtml::encode($data->prefix); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('currency')); ?>:</b>
	<?php echo CHtml::encode($data->currency); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('country_id')); ?>:</b>
	<?php echo CHtml::encode($data->country_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
	<?php echo CHtml::encode($data->model); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('net_payment')); ?>:</b>
	<?php echo CHtml::encode($data->net_payment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deal')); ?>:</b>
	<?php echo CHtml::encode($data->deal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_payment_amount')); ?>:</b>
	<?php echo CHtml::encode($data->post_payment_amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_date')); ?>:</b>
	<?php echo CHtml::encode($data->end_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('daily_cap')); ?>:</b>
	<?php echo CHtml::encode($data->daily_cap); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sizes')); ?>:</b>
	<?php echo CHtml::encode($data->sizes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('has_s2s')); ?>:</b>
	<?php echo CHtml::encode($data->has_s2s); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('callback')); ?>:</b>
	<?php echo CHtml::encode($data->callback); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('placeholder')); ?>:</b>
	<?php echo CHtml::encode($data->placeholder); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('has_token')); ?>:</b>
	<?php echo CHtml::encode($data->has_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('commercial_name')); ?>:</b>
	<?php echo CHtml::encode($data->commercial_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo CHtml::encode($data->state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zip_code')); ?>:</b>
	<?php echo CHtml::encode($data->zip_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contact_com')); ?>:</b>
	<?php echo CHtml::encode($data->contact_com); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_com')); ?>:</b>
	<?php echo CHtml::encode($data->email_com); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contact_adm')); ?>:</b>
	<?php echo CHtml::encode($data->contact_adm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_adm')); ?>:</b>
	<?php echo CHtml::encode($data->email_adm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('entity')); ?>:</b>
	<?php echo CHtml::encode($data->entity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tax_id')); ?>:</b>
	<?php echo CHtml::encode($data->tax_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prospect')); ?>:</b>
	<?php echo CHtml::encode($data->prospect); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pdf_name')); ?>:</b>
	<?php echo CHtml::encode($data->pdf_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pdf_agreement')); ?>:</b>
	<?php echo CHtml::encode($data->pdf_agreement); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('foundation_date')); ?>:</b>
	<?php echo CHtml::encode($data->foundation_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('foundation_place')); ?>:</b>
	<?php echo CHtml::encode($data->foundation_place); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_account_name')); ?>:</b>
	<?php echo CHtml::encode($data->bank_account_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_account_number')); ?>:</b>
	<?php echo CHtml::encode($data->bank_account_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('branch')); ?>:</b>
	<?php echo CHtml::encode($data->branch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_name')); ?>:</b>
	<?php echo CHtml::encode($data->bank_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('swift_code')); ?>:</b>
	<?php echo CHtml::encode($data->swift_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('percent_off')); ?>:</b>
	<?php echo CHtml::encode($data->percent_off); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('url')); ?>:</b>
	<?php echo CHtml::encode($data->url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('use_alternative_convention_name')); ?>:</b>
	<?php echo CHtml::encode($data->use_alternative_convention_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('has_api')); ?>:</b>
	<?php echo CHtml::encode($data->has_api); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('use_vectors')); ?>:</b>
	<?php echo CHtml::encode($data->use_vectors); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('query_string')); ?>:</b>
	<?php echo CHtml::encode($data->query_string); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('token1')); ?>:</b>
	<?php echo CHtml::encode($data->token1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('token2')); ?>:</b>
	<?php echo CHtml::encode($data->token2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('token3')); ?>:</b>
	<?php echo CHtml::encode($data->token3); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_percentage')); ?>:</b>
	<?php echo CHtml::encode($data->publisher_percentage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rate')); ?>:</b>
	<?php echo CHtml::encode($data->rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('users_id')); ?>:</b>
	<?php echo CHtml::encode($data->users_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('account_manager_id')); ?>:</b>
	<?php echo CHtml::encode($data->account_manager_id); ?>
	<br />

	*/ ?>

</div>