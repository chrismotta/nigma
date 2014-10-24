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
    echo '<p><strong>External Name: </strong>' . $campaignName . ' </p>';
    echo '<hr/>';
    
    echo '<p><strong>'.$network->name.' (old format): </strong>http://kickadserver.mobi/clicksLog?cid='.$model->id.'&nid='.$network->id;
    if($network->has_s2s) echo '&ntoken=' . $network->placeholder;
    echo '</p>';

    echo '
    <p><strong>'.$network->name.' (new format): </strong>http://kickadserver.mobi/clicksLog/tracking/'.$model->id.'/';
    if($network->has_s2s) echo '?ntoken=' . $network->placeholder;
    echo '</p>';
    echo '
    <hr/>
    <p><strong>S2S: </strong>http://kickadserver.mobi/convLog?ktoken=&lt;'.$model->opportunities->server_to_server.'&gt;</p>
    ';
    echo '';
    //<button type="button" class="btn btn-default btn-sm">copy</button> 
    ?>
</div>

<div class="modal-footer">
    Copy and paste the redirect URL into the traffic source.
</div>

