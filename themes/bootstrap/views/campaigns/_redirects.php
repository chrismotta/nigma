<?php
/* @var $this CampaignsController
 * @var $model Campaigns 
 * @var $form CActiveForm 
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Redirects for campaign #<?php echo $model->id ?></h4>
</div>

<div class="modal-body">
    <?php
    echo '
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Airpush: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$model->id.'&nid=1 </p>
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Reporo: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$model->id.'&nid=2 </p>
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Ajillion: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$model->id.'&nid=3 </p>
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Adwords: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$model->id.'&nid=4 </p>
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Kimia: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$model->id.'&nid=5 </p>
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Leadbolt: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$model->id.'&nid=6 </p>
    ';
    ?>
    <hr/>
    <p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>S2S: </strong>http://kickadserver.mobi/test/convLog?mytoken=&lt;mytoken&gt; </p>
</div>

<div class="modal-footer">
    Copy and paste the redirect URL into the traffic source.
</div>

