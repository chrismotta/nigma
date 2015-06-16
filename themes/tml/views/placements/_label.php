<?php
/* @var $this CampaignsController
 * @var $model Campaigns 
 * @var $form CActiveForm 
 * @var $campaignName
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Label for placement #<?php echo $model->id ?></h4>
</div>

<div class="modal-body">
    <?php

    // tittle
    echo '<p><strong>External Name: </strong>' . $label . ' </p>';

    ?>
</div>

<div class="modal-footer">
    Copy and paste the label into the traffic source.
</div>

