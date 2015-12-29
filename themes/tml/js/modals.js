function getIframe(src, tittle, height){
	
	var iframe = '';

	iframe+= '<div class="modal-header">';
	iframe+= '<a class="close" data-dismiss="modal">&times;</a>';
	iframe+= '<h4>'+tittle+'</h4>';
	iframe+= '</div>';
	iframe+= '<div class="modal-body">';
	iframe+= '	<iframe src="'+src+'" width="100%" height="'+height+'px" frameborder="0" scrolling="no"></iframe>';
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
	function openModal(that){
	    // e.preventDefault();
	    
	    // var that = $(this);
		var src = that.href;
		var tittle = 'Transaction Count';
		var height = 605;

		$('#modalClients').modal('toggle');

		$('#modalClients').html( getIframe(src, tittle, height) );

		return false;
	}