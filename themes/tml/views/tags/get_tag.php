<?php 

$width = $model->bannerSizes->width;
$height = $model->bannerSizes->height;
$id = $model->id;

// old format // DEPRECATED
$phpTextIframe = '<iframe src="http://bidbox.co/tag/'. $id . '?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>" width="'. $width .'" height="'. $height .'" frameborder="0" scrolling="no" ></iframe>';
$phpTextJs = '<script type="text/javascript" src="http://bidbox.co/tag/js/'. $id . '?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>&width='. $width .'&height='. $height .'"></script>';
$phpTextJsp = '<script type="text/javascript" src="http://bidbox.co/tag/jsp/'. $id . '?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>&width='. $width .'&height='. $height .'"></script>';
$phpTextJsi = '<script type="text/javascript" src="http://bidbox.co/tag/jsi/'. $id . '?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>&width='. $width .'&height='. $height .'"></script>';
$phpTextURL = 'http://bidbox.co/tag/url/'. $id . '?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>';

$iframeText1 = '<iframe src=\"http://bidbox.co/tag/'. $id . '?pid=';
$iframeText2 = '&pubid=<INSERT_PUBID_MACRO_HERE>\" width=\"'. $width .'\" height=\"'. $height .'\" frameborder=\"0\" scrolling=\"no\" ></iframe>';

$jsText1 = '<script type=\"text/javascript\" src=\"http://bidbox.co/tag/js/'. $id . '?pid=';
$jsText2 = '&pubid=<INSERT_PUBID_MACRO_HERE>&width='. $width .'&height='. $height .'\"></script>';
?>

<div class="alert alert-info">
	<div class="form-group form-horizontal">
		<?php if($parent=='c') echo '<strong>Get Tag #'.$model->id.'</strong>'; ?>

		<div class="control-group">
			<div class="control-label">Publishers</div>
			<div class="controls">
		<?php 
		echo CHtml::dropDownList('publishers', null, $publishers, 
		    array(
		        'prompt'   => 'Select a publisher',
		        'onChange' => '
		            if ( ! this.value) {
		                $(".sites-dropdownlist").html("<option value=\"\">Select a site</option>");
		                $(".sites-dropdownlist").prop( "disabled", true );
		                $(".placements-dropdownlist").html("<option value=\"\">Select a placement</option>");
		                $(".placements-dropdownlist").prop( "disabled", true );
		                return;
		            }
		            $.post(
		                "../getSites/"+this.value,
		                "",
		                function(data)
		                {
		                    $(".sites-dropdownlist").html(data);
		                    $(".sites-dropdownlist").prop("disabled", false);
			                $(".placements-dropdownlist").html("<option value=\"\">Select a placement</option>");
			                $(".placements-dropdownlist").prop( "disabled", true );
		                }
		            )
		        '
		        ));
		?>
			</div>
			<div class="control-label">Sites</div>
			<div class="controls">
		<?php 
		echo CHtml::dropDownList('sites', null, array(), 
			array(
			    'prompt'   => 'Select a site',
			    'class'    => 'sites-dropdownlist',
			    'disabled' => true,
		        'onChange' => '
		            if ( ! this.value) {
		                $(".placements-dropdownlist").html("<option value=\"\">Select a placement</option>");
		                $(".placements-dropdownlist").prop( "disabled", true );
		                return;
		            }
		            $.post(
		                "../getPlacements/"+this.value,
		                "",
		                function(data)
		                {
		                    $(".placements-dropdownlist").html(data);
		                    $(".placements-dropdownlist").prop("disabled", false);
		                }
		            )
		        '
			    ));
		?>
			</div>
			<div class="control-label">Placements</div>
			<div class="controls">
		<?php 
		echo CHtml::dropDownList('placements', null, array(), 
			array(
			    'prompt'   => 'Select a placement',
			    'class'    => 'placements-dropdownlist',
			    'disabled' => true,
			    'onChange' => '
			    	var iframe = $("#tag_content_iframe").val();
			    	var js = $("#tag_content_js").val();
			    	// var jsi = $("#tag_content_jsi").val();
			    	// var jsp = $("#tag_content_jsp").val();
			    	// var url = $("#tag_content_url").val();

		            $(".tag_content_iframe").val( iframe.replace(replaceMacro, this.value) );
		            $(".tag_content_js").val( js.replace(replaceMacro, this.value) );
		            // $("#tag_content_jsi").val( jsi.replace(replaceMacro, this.value) );
		            // $("#tag_content_jsp").val( jsp.replace(replaceMacro, this.value) );
		            // $("#tag_content_url").val( url.replace(replaceMacro, this.value) );

		            replaceMacro = this.value;

		            var downloadHref = $("#download-txt").attr("href");
		            var downloadHrefSpl = downloadHref.split("?"); 
		            downloadHref = downloadHrefSpl[0] + "?pid=" + this.value + "&protocol=" + replaceProtocol;

		            console.log(downloadHref);
		            $("#download-txt").attr("href", downloadHref);
		            return;
		            ',
			    ));


		?>
			</div>
			<div class="control-label">Protocol</div>
			<div class="controls">
		<?php 
		echo CHtml::radioButtonList('ssl', 'http://', array('http://'=>'HTTP', 'https://'=>'HTTPS'), 
			array(
			    'prompt'   => 'Select a placement',
			    'class'    => 'placements-radio',
			    'onChange' => '
			    	var iframe = $(".tag_protocol_iframe").val();
			    	var js = $(".tag_protocol_js").val();

		            $(".tag_protocol_iframe").val( iframe.replace(replaceProtocol, this.value) );
		            $(".tag_protocol_js").val( js.replace(replaceProtocol, this.value) );

		            replaceProtocol = this.value;

		            var downloadHref = $("#download-txt").attr("href");
		            var downloadHrefSpl = downloadHref.split("?"); 
		            downloadHref = downloadHrefSpl[0] + "?pid=" + replaceMacro + "&protocol="+this.value;

		            console.log(downloadHref);
		            $("#download-txt").attr("href", downloadHref);
		            return;
		            ',
			    ));


		?>
			</div>
		</div>

		<?php 

		$domains[] = array(
			'domain' => 'bidmachine.co',
			'name' => 'MAIN DOMAIN (HTTPS ALLOWED)',
			'secure' => true,
		);
		$domains[] = array(
			'domain' => 'mobformance.com',
			'name' => 'ALT_1 DOMAIN (HTTP ONLY)',
			'secure' => false,
		);
		$domains[] = array(
			'domain' => 'performile.com',
			'name' => 'ALT_2 DOMAIN (HTTP ONLY)',
			'secure' => false,
		);

		foreach ($domains as $value) {

			$phpTextIframe = '<iframe src="http://req.'.$value['domain'].'/'. $id . '?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>" width="'. $width .'" height="'. $height .'" frameborder="0" scrolling="no" ></iframe>';
			
			$phpTextJs = '<script type="text/javascript" src="http://req.'.$value['domain'].'/js/'. $id . '?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>&width='. $width .'&height='. $height .'"></script>';
			
			echo '<div class="text-right code-area-separator">';
			echo $value['name'] . ': ' . $value['domain'];
			echo '</div>';

			echo '<div class="text-left code-area">';
			echo 'Iframe';
			
			echo CHtml::textArea('tag_content_iframe', $phpTextIframe,
				array('class' => $value['secure'] ? 'tag_content_iframe tag_protocol_iframe' : 'tag_content_iframe', 
				'readonly' => true,
				'style'=>'width:100%;height:60px;cursor:text'));
			
			echo '</div>';

			echo '<div class="text-left code-area">';
			echo 'Javascript';

			echo CHtml::textArea('tag_content_js', $phpTextJs,
				array('class' => $value['secure'] ? 'tag_content_js tag_protocol_js' : 'tag_content_js', 
				'readonly' => true,
				'style'=>'width:100%;height:60px;cursor:text')); 
			
			echo '</div>';

		}

		?>



		<?php /*
		<div class="text-right code-area-separator">
		OLD FORMAT TAGS
		</div>

		<div class="text-left code-area">
		Iframe
		<?php echo CHtml::textArea('tag_content_iframe', $phpTextIframe,
			array('id'=>'tag_content_iframe', 
			'readonly' => true,
			'style'=>'width:100%;height:60px;cursor:text')); ?>
		</div>

		<div class="text-left code-area">
		Javascript
		<?php echo CHtml::textArea('tag_content_js', $phpTextJs,
			array('id'=>'tag_content_js', 
			'readonly' => true,
			'style'=>'width:100%;height:60px;cursor:text')); ?>
		</div>

		<div class="text-left code-area">
		Javascript (incent)
		<?php echo CHtml::textArea('tag_content_jsi', $phpTextJsi,
			array('id'=>'tag_content_jsi', 
			'readonly' => true,
			'style'=>'width:100%;height:60px;cursor:text')); ?>
		</div>

		<div class="text-left code-area">
		Javascript (pop)
		<?php echo CHtml::textArea('tag_content_jsp', $phpTextJsp,
			array('id'=>'tag_content_jsp', 
			'readonly' => true,
			'style'=>'width:100%;height:60px;cursor:text')); ?>
		</div>

		<div class="text-left code-area">
		Landing URL
		<?php echo CHtml::textField('tag_content_url', $phpTextURL,
			array('id'=>'tag_content_url', 
			'readonly' => true,
			'style'=>'width:100%;cursor:text')); ?>
		</div>
		*/ ?>

		<div class="text-right">
		<?php echo CHtml::link('Download .txt',
			array('getTxt','id'=>$model->id),
			array('id'=>'download-txt')
			); ?>
		</div>
	</div>
</div>

<?php if($parent=='c') echo CHtml::link('<- Back to list',array('adminByCampaign','id'=>$model->campaigns_id)); ?>

<?php Yii::app()->clientScript->registerScript('replaceMacro', '
    var replaceMacro = "<placementID>";
    var replaceProtocol = "http://";
', CClientScript::POS_END); ?>
