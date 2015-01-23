<?php
/* @var $this AdvertisersController
 * @var $model Advertiser 
 * @var $form CActiveForm 
 * @url $url
 * @var $timeLeft
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>External form for Provider #<?php echo $model->id ?></h4>
</div>

<div class="modal-body">
    <?php
    echo '
    <p style="word-break: break-all;"><strong>External Form URL: </strong><br/>' . $url . '</p>
    <hr/>
    ';
    ?>
    <small>This link will be available for <?php echo $timeLeft; ?> hours.</small>
</div>

<div class="modal-footer">
    Copy and paste the external form URL.
</div>