<?php
/* @var $this VectorsController
 * @var $model Vectors 
 * @var $form CActiveForm 
 * @var $vectorName
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Redirects for vector #<?php echo $model->id ?></h4>
</div>

<div class="modal-body">
    <?php
    echo '
    <p><strong>External Name: </strong>' . $vectorName . ' </p>
    <hr/>
    ';
    echo '
    <p><strong>Redirect: </strong>http://tmlbox.co/clicklog/v/'.$model->id.'/</p>
    ';
    //<button type="button" class="btn btn-default btn-sm">copy</button> 
    ?>
</div>

<div class="modal-footer">
    Copy and paste the redirect URL into the traffic source.
</div>

