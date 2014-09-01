<?php
/* @var $this CampaignsController
 * @var $model Campaigns 
 * @var $form CActiveForm 
 * @var $campaignName
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Redirects for campaign #<?php echo $model->id ?></h4>
</div>

<div class="modal-body">
    <?php
    echo '
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>External Name: </strong>' . $campaignName . ' </p>
    <hr/>
    ';
    echo '<p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>'.$network->name.': </strong>http://kickadserver.mobi/clicksLog?cid='.$model->id.'&nid='.$network->id.' </p>';
    echo '
    <hr/>
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>S2S: </strong>http://kickadserver.mobi/convLog?ktoken=&lt;'.$model->opportunities->server_to_server.'&gt; </p>
    ';
    ?>
</div>

<div class="modal-footer">
    Copy and paste the redirect URL into the traffic source.
</div>

