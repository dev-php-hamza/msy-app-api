$(document).ready(function(){
	$(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	});

	$('.add__btn').click(function() {
		var type = $('#type-of-coupon').val();
		if (type == 'std_bundle') {
			var structure = $('<div class="row d-inputs"><div class="col-md-2"><label class="control-label" for="upc">UPC</label><input type="number" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)"></div><div class="col-md-2"><label class="control-label" for="title">Title</label><input type="text" placeholder="Title" id="title" readonly></div><div class="col-md-2"><label class="control-label" for="current_price">Current price</label><input type="number" placeholder="Current price" id="current_price" readonly></div><div class="col-md-2"><label class="control-label" for="quantity">Quantity</label><input type="number" placeholder="Quantity" id="quantity" onchange="getDiscountedPrice(this)" name="quantity[{{ $product->id }}]"></div><div class="col-md-2"><label class="control-label" for="total_price">Total price</label><input type="price" placeholder="total price" id="total_price" readonly value=0 name="total_price[{{ $product->id }}]"></div><div class="col-md-2"><label class="control-label" for="discount_type">Discount Type</label><select name="discount_type[{{ $product->id }}]" id="discount_type" onchange="discountValue(this)"><option value="percentage">Percentage</option><option value="price">Price</option></select></div><div class"col-md-2"><label class="control-label" for="discount_percentage">Discount</label><input type="price" placeholder="%" id="discount_percentage" name="discounted_perc[{{ $product->id }}]" onchange="getDiscountedpercentage(this)"></div><div class="col-md-2"><label class="control-label" for="discounted_price">Discounted Price</label><input type="price" placeholder="Discounted Price" id="discounted_price" value=0 readonly name="discounted_price[{{ $product->id }}]"></div><div class="col-md-2"><button class="remove__btn"><i class="fas fa-minus"></i></button></div><div class="row error"><div class="col-md-12"><div class="alert alert-danger append-error-div fade show" ><strong>Error!</strong> Entered Upc is not found. Try different UPC.</div></div></div></div>');
		}
		else if (type == 'mix_and_match')
		{
			var mamType = $("#mix_and_match_type").val();
			if(mamType == 'different_cost_products'){
				var structure = $('<div class="row d-inputs"><div class="col-md-3"><label class="control-label" for="upc">UPC</label><input type="number" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)"></div><div class="col-md-3"><label class="control-label" for="title">Title</label><input type="text" placeholder="Title" id="title" disabled></div><div class="col-md-2"><label class="control-label" for="current_price">Current price</label><input type="number" placeholder="Current price" id="current_price" disabled></div><div class="col-md-2 mix-and-match-product-type"><label class="control-label" for="mix_and_match_product_type">Type</label><select name="mix_and_match_product_type[]" id="mix_and_match_product_type"><option value="buy">Buy</option><option value="select">Select</option></select></div><div class="col-md-2"><button class="remove__btn"><i class="fas fa-minus"></i></button></div><div class="row error"><div class="col-md-12"><div class="alert alert-danger append-error-div fade show" ><strong>Error!</strong> Entered Upc is not found. Try different UPC.</div></div></div></div>');
			}else{
				var structure = $('<div class="row d-inputs"><div class="col-md-3"><label class="control-label" for="upc">UPC</label><input type="number" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)"></div><div class="col-md-3"><label class="control-label" for="title">Title</label><input type="text" placeholder="Title" id="title" disabled></div><div class="col-md-2"><label class="control-label" for="current_price">Current price</label><input type="number" placeholder="Current price" id="current_price" disabled></div><div class="col-md-2 mix-and-match-product-type" style="display: none;"><label class="control-label" for="mix_and_match_product_type">Type</label><select name="mix_and_match_product_type[]" id="mix_and_match_product_type"><option value="buy">Buy</option><option value="select">Select</option></select></div><div class="col-md-2"><button class="remove__btn"><i class="fas fa-minus"></i></button></div><div class="row error"><div class="col-md-12"><div class="alert alert-danger append-error-div fade show" ><strong>Error!</strong> Entered Upc is not found. Try different UPC.</div></div></div></div>');
			}
		}
		else{
	    	var structure = $('<div class="row d-inputs"><div class="col-md-3"><label class="control-label" for="upc">UPC</label><input type="number" name="" placeholder="UPC" id="upc" onchange="getProductByUPC(this)"></div><div class="col-md-3"><label class="control-label" for="title">Title</label><input type="text" placeholder="Title" id="title" disabled></div><div class="col-md-2"><label class="control-label" for="current_price">Current price</label><input type="number" placeholder="Current price" id="current_price" disabled></div><div class="col-md-2"><button class="remove__btn"><i class="fas fa-minus"></i></button></div><div class="row error"><div class="col-md-12"><div class="alert alert-danger append-error-div fade show" ><strong>Error!</strong> Entered Upc is not found. Try different UPC.</div></div></div></div>');
		}
	   $('.row-duplicate').append(structure); 
	});

	// Add Condition Row
	$('.add_cond_btn').click(function() {
	    var structure = $('<div class="row d-inputs"><div class="col-md-2"><label class="control-label" for="buy_q">Buy</label><input type="number" name="buy_q[]" id="buy_q" value="1"></div><div class="col-md-2"><label class="control-label" for="prod_q">Of Product(s)</label><input type="number" name="prod_q[]" id="prod_q" value="1"></div><div class="col-md-2"><button class="remove_cond_btn"><i class="fas fa-minus"></i></button></div></div>');
	   $('.condition-row-duplicate').append(structure); 
	});

	// Remove Condition Row
	$("body").on('click', '.remove_cond_btn', function(event) {
		var elem = $(this);
		elem.parents('.d-inputs').remove();
		return false;	    
  	});

	$("body").on('click', '.remove__btn', function(event) {
		let elem = $(this);
		let productCouponId = elem.attr("id");
		
		if(typeof(productCouponId) != "undefined" && productCouponId !== null ) {
			let data  =productCouponId.split (":");
			let couponId = data[0];
			let productId = data[1];
		    
		    let base_url = window.location.origin;    
		    $.ajaxSetup({
		    	headers: {
		    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	}
		    });
		    $.ajax({
		    	type: 'POST',
		    	url: base_url+'/admin/coupon/'+couponId+'/product/'+productId+'/remove',
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
		url: base_url+'/admin/coupon/'+country_id+'/products/'+upc,
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(res){
			$(elem).parent().parent().find('.alert-danger').hide();
			let product = res['product'];			
			if (Object.keys(product).length > 0 ) {

				$(elem).css("cssText", "color: black !important;");
				$(elem).parent().parent().find('#discounted_price').attr('name', 'discounted_price['+product["id"]+']');
				$(elem).parent().parent().find('#discount_type').attr('name', 'discount_type['+product["id"]+']');
				$(elem).parent().parent().find('#total_price').attr('name', 'total_price['+product["id"]+']');
				$(elem).parent().parent().find('#discount_percentage').attr('name', 'discount_percentage['+product["id"]+']');
				$(elem).parent().parent().find('#title').val(product['desc']);
				$(elem).parent().parent().find('#upc').attr('name', 'products['+product["id"]+']');
				$(elem).parent().parent().find('#quantity').attr('name', 'quantity['+product["id"]+']');
				$(elem).parent().parent().find('#current_price').val(product['unit_retail']);
			}else{
				$(elem).parent().parent().find('.alert-danger').show();
				$(elem).parent().parent().find('#title').val('');
				$(elem).parent().parent().find('#current_price').val('');
			}
		}
	});
}

function getDiscountedPrice(elem) {
	let base_url = window.location.origin;
	let country_id = $('.add__btn').attr('id');
	let upc = $(elem).parent().parent().find('#upc').val();
	let discount = $(elem).parent().parent().find('#discount_percentage').val();
	let quantity = elem.value;
	let discount_type = $(elem).parent().parent().find('#discount_type').val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'GET',
		url: base_url+'/admin/coupon/'+country_id+'/products/'+upc,
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(res){
			$(elem).parent().parent().find('.alert-danger').hide();
			let product = res['product'];
			var totalPrice = product['unit_retail'] * quantity;
			
			if(discount > 0){
				if (discount_type == 'percentage') {
					var discountPr = (product['unit_retail'] * quantity * discount)/100;
				}
				if (discount_type == 'price') {
					var discountPr = discount;
				}
				var discountPrice = totalPrice - discountPr;
			}else{
				var discountPrice = totalPrice;
			}
			
			// if (discountPr > 0) {
			// 	var discountPrice = totalPrice - discountPr;
			// }else{
			// 	var discountPrice = 0;
			// }

			// sum discount
			// var sum = $('#bundle_sum').val();
			// var total_sum = Number(sum) + Number(discountPrice);
			
			if (Object.keys(product).length > 0 ) {

				$(elem).css("cssText", "color: black !important;");
	
				$(elem).parent().parent().find('#quantity').attr('name', 'quantity['+product["id"]+']');
				$(elem).parent().parent().find('#total_price').val(totalPrice.toFixed(2));
				$(elem).parent().parent().find('#discounted_price').val(discountPrice.toFixed(2));
				// $('#bundle_sum').val(total_sum);
			}else{
				$(elem).parent().parent().find('.alert-danger').show();
				$(elem).parent().parent().find('#title').val('');
				$(elem).parent().parent().find('#current_price').val('');
			}
		}
	});
}

function getDiscountedpercentage(elem) {
	let base_url = window.location.origin;
	let country_id = $('.add__btn').attr('id');
	let upc = $(elem).parent().parent().find('#upc').val();
	let totalPrice = $(elem).parent().parent().find('#total_price').val();
	let discountPer = elem.value;
	let discount_type = $(elem).parent().parent().find('#discount_type').val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'GET',
		url: base_url+'/admin/coupon/'+country_id+'/products/'+upc,
		dataType: 'json',
		beforeSend: function(){
		},
		success: function(res){
			$(elem).parent().parent().find('.alert-danger').hide();
			let product = res['product'];
			if (discount_type == 'percentage') {
				var disc = 	(totalPrice * discountPer)/100;
				var discount = 	totalPrice - disc;	
			}

			if (discount_type == 'price') {
				var discount = 	totalPrice - discountPer;	
			}
			// alert(disc);
			// if (disc > 0) {
			// 	var discount = 	totalPrice - disc;
			// }

			// sum 
			// var sum = $('#bundle_sum').val();
			// alert(sum);
			// var total_sum = Number(sum) + Number(discount);
			if (Object.keys(product).length > 0 ) {

				$(elem).css("cssText", "color: black !important;");
				$(elem).parent().parent().find('#quantity').attr('name', 'quantity['+product["id"]+']');
				$(elem).parent().parent().find('#discounted_price').val(discount.toFixed(2));
				// $('#bundle_sum').val(total_sum);
			}else{
				$(elem).parent().parent().find('.alert-danger').show();
				$(elem).parent().parent().find('#title').val('');
				$(elem).parent().parent().find('#current_price').val('');
			}
		}
	});
}

function show_products_and_conditions_section() {
	var mamType = $("#mix_and_match_type").val();
	if(mamType == 'different_cost_products'){
		$(".mix-and-match-product-type").show();
	}else{
		$(".mix-and-match-product-type").hide();
	}
	$("#products-and-conditions-section").show();
}

function discountValue(elem) {
	let totalPrice = $(elem).parent().parent().find('#total_price').val();
	let discount = $(elem).parent().parent().find('#discount_percentage').val();
	var value = elem.value;
	if (value == 'price') {
		$(elem).parent().parent().find('#discount_percentage').attr("placeholder", "$");
		if(discount > 0){
			var discountPrice = totalPrice - discount;
			$(elem).parent().parent().find('#discounted_price').val(discountPrice.toFixed(2));
		}
	}
	if (value == 'percentage') {
		$(elem).parent().parent().find('#discount_percentage').attr("placeholder", "%");
		if(discount > 0){
			var disc = 	(totalPrice * discount)/100;
			var discountPrice = totalPrice - disc;
			$(elem).parent().parent().find('#discounted_price').val(discountPrice.toFixed(2));
		}
	}
}
