function getLocations(elem){
	let countryId = elem.value;
	let base_url = window.location.origin;
	if (countryId != '') {
	  	$('.locations').remove();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'get',
			url: base_url+"/admin/country/"+countryId+"/locations",
			dataType: "json",
			beforeSend: function(){
			},
			success: function(data){
				let newElement = '';
				$.each(data['locations'], function(index,location){
					let locationName = location['name'];
					let elemId = locationName.replace(/\ /g, '_');
					console.log(elemId);
					newElement += "<div class='locations'><div class='form-group'><label class='control-label col-sm-2' for='"+elemId+"'>"+location['name']+"</label><div class='col-sm-10'><input type='text' class='form-control' id='"+elemId+"' name='locations["+location['id']+"]' value='0'></div></div></div>";

				});

      			newElement += "<div class='locations'><div class='form-group'><div class='col-sm-offset-2 col-sm-10'><input type='file' multiple='true' name='file[]'></div></div></div>";
				newElement += "<div class='locations'><div class='form-group'><div class='col-sm-offset-2 col-sm-10'><input type='submit' class='btn btn-primary'></div></div></div>";

				$("#productForm").append(newElement);
			}
		});
	}
}

function getProductByUPC(elem){
	let base_url = window.location.origin;
	let upc = $('#upc').val();
	let countryId = $('#countries :selected').val();
	let prodName = $('#prodName').val();
	let requestData = {'upc':upc, 'countryId': countryId, 'prodName': prodName};
	let upcOrName = 'false';

	if ((upc !== "undefined" && upc !== null && upc !== '') || (prodName !== "undefined" && prodName !== null && prodName !== '' )) {
		$('#leastOne-error').hide();
	}else{
		upcOrName = 'true';
		$('#leastOne-error').show();
	}

	if (countryId !== "undefined" && countryId !== null && countryId !== '') {
		$('#country-error').hide();
	}else{
		$('#country-error').show();
	}

	if (countryId != "undefined" && countryId !== null && countryId !== '' && upcOrName =='false' ) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'GET',
			data: requestData,
			url: base_url+'/admin/product/search',
			dataType: 'json',
			beforeSend: function(){
			},
			success: function(res){
				let products = res['products'];	
				if (Object.keys(products).length > 0 ) {
					$('.not__found').hide();
					$('.found__product').empty();
					let newElem = "<div class='products_table'><table><tr><th>#</th><th>upc</th><th>Desc</th><th>Size</th><th>Item Packing</th><th>Unit Retail</th><th>Action</th></tr><tbody id='table-body'>";
					$.each(products,function(index,product){
						index = index + 1;
						newElem += '<tr><td>'+index+'</td>';
						newElem += "<td>"+product['upc']+"</td>";
						newElem += "<td>"+product['desc']+"</td>";
						newElem += "<td>"+product['size']+"</td>";
						newElem += "<td>"+product['item_packing']+"</td>";
						newElem += "<td>"+product['unit_retail']+"</td>";
						newElem += "<td><a href='"+base_url+"/admin/products/"+product['id']+"' class='btn btn-info'>Details</a></td>";
					});
					newElem += "</tr></tbody></table></div>";
		            $('.found__product').append(newElem);
				}else{
					$('#leastOne-error').hide();
					// $('#country-error').hide();
					$('.found__product').empty();
					$('.not__found').show();
				}
			}
		});
	}
		
}