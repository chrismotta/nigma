<?php

echo $form->textFieldRow($model, 'prefix', array('class'=>'span3'));
echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
echo $form->dropDownListRow($model, 'currency', KHtml::enumItem($model, 'currency'), array('prompt' => 'Select a currency'));
echo $form->checkboxRow($model, 'has_s2s', array(
        'onChange' => '
          if (this.checked == "1")
            $(".has_s2s").show();
          else
            $(".has_s2s").hide();

          return;
          '
    ));
echo '<div style="display: ' . ($model->has_s2s ? 'block' : 'none') . ';" class="has_s2s">';
echo $form->textFieldRow($model, 'callback', array('class'=>'span3'));
echo $form->checkboxRow($model, 'has_token', array(
        'onChange' => '
          if (this.checked == "1")
            $(".has_token").show();
          else
            $(".has_token").hide();

          return;
          '
    ));
echo '<div style="display: ' . ($model->has_token ? 'block' : 'none') . ';" class="has_token">';
echo $form->textFieldRow($model, 'placeholder', array('class'=>'span3'));
echo '</div>';
echo '</div>';
echo '<hr/>';