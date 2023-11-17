$(document).ready(function(){
  
	$(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	});

	$('.add__btn').click(function() {
	    var structure = $('<div class="row d-inputs"><div class="col-md-3"><label class="control-label" for="upc">UPC</label><input type="text" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)"></div><div class="col-md-3"><label class="control-label" for="title">Title</label><input type="text" name="" placeholder="Title" id="title" disabled></div><div class="col-md-2"><label class="control-label" for="current_price">Current price</label><input type="number" name="" placeholder="Current price" id="current_price" disabled></div><div class="col-md-2"><label class="control-label" for="sale_price">Sales price</label><input type="number" placeholder="0.00" step="0.01" id="sale_price" disabled></div><div class="col-md-2"><button class="remove__btn"><i class="fas fa-minus"></i></button></div><div class="row error"><div class="col-md-12"><div class="alert alert-danger append-error-div fade show" ><strong>Error!</strong> Entered Upc is not found. Try different UPC.</div></div></div></div>');
	   $('.row-duplicate').append(structure); 
	});

	$("body").on('click', '.remove__btn', function(event) {
		let elem = $(this);
		let productPromoId = elem.attr("id");
		
		if(typeof(productPromoId) != "undefined" && productPromoId !== null ) {
			let data  =productPromoId.split (":");
			let promotionId = data[0];
			let productId = data[1];
		    
		    let base_url = window.location.origin;    
		    $.ajaxSetup({
		    	headers: {
		    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	}
		    });
		    $.ajax({
		    	type: 'POST',
		    	url: base_url+'/admin/promotion/'+promotionId+'/product/'+productId+'/remove',
		    	dataType: 'json',
		    	success: function(res){
		    		if (res['status']) {
		    			elem.parents('.d-inputs').remove();
		    		}else{
		    			alert("something happend..!! Try again");
		    		}
		    	}
		    });
		}else{
			elem.parents('.d-inputs').remove();	
		}
		return false;	    
  	});
});

function getProductByUPC(elem){
	let base_url = window.location.origin;
	let country_id = $('.add__btn').attr('id');
	let upc = elem.value;
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'GET',
		url: base_url+'/admin/promotion/'+country_id+'/products/'+upc,
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(res){
			$(elem).parent().parent().find('.alert-danger').hide();
			let product = res['product'];			
			if (Object.keys(product).length > 0 ) {

				$(elem).css("cssText", "color: blue !important;");
				$(elem).parent().parent().find('#title').val(product['desc']);
				$(elem).parent().parent().find('#current_price').val(product['unit_retail']);
				if (product['regular_retail']) {
					$(elem).parent().parent().find('#current_price').val(product['regular_retail']);
				}
				$(elem).parent().parent().find('#sale_price').val(product['unit_retail']);
				$(elem).parent().parent().find('#sale_price').removeAttr('disabled');
				$(elem).parent().parent().find('#sale_price').attr('name', 'products['+product["id"]+']');
			}else{
				$(elem).parent().parent().find('.alert-danger').show();
				$(elem).parent().parent().find('#title').val('');
				$(elem).parent().parent().find('#current_price').val('');
				$(elem).parent().parent().find('#sale_price').val('');
				$(elem).parent().parent().find('#sale_price').attr('disabled','disabled');
			}
		}
	});
}


