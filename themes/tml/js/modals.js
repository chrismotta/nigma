function getIframe(src, tittle, gridId){

	var iframe = '';

	iframe+= '<div class="modal-header">';
	iframe+= '<a class="close" data-dismiss="modal" onclick="updateGrid( \''+gridId+'\' );" >&times;</a>';
	iframe+= '<h4>'+tittle+'</h4>';
	iframe+= '</div>';
	iframe+= '<div class="modal-body">';
	iframe+= '	<iframe src="'+src+'" style="width:100%;height:500px;" frameborder="0" scrolling="no"></iframe>';
	iframe+= '</div>';
	iframe+= '<div class="modal-footer">';
	iframe+= '    Fields with <span class="required">*</span> are required.';
	iframe+= '</div>';
	
	return iframe;
}

// $(document).ready(function(){

// 	$('.openModal').click(

// 	});

// });

function updateGrid(gridId){
	// console.log(gridId);
	if(gridId){
		$.fn.yiiGridView.update(gridId);
		// console.log('grid updated');
	}
}

function openModal(that){
	var src     = that.href;
	var modalId = $(that).attr('data-modal-id');
	var tittle  = $(that).attr('data-modal-title');
	var gridId  = $(that).attr('data-grid-id');

	$('#'+modalId).modal('toggle'); 
	$('#'+modalId).html( getIframe(src, tittle, gridId) );

	$('.modal-body iframe').load(function (){
		var height = $('.modal-body iframe').contents().find('fieldset').height();
		console.log(height);
		$('.modal-body iframe').height(height);
		console.log(height);
	});

	return false;
}