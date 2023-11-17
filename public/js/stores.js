function getLocations(elem){
    let countryId = elem.value;
    let base_url = window.location.origin;
    $("#storecode").val('');
    if (countryId != '') {
        $('#locations').empty();
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
                let newOpt = '';
                newOpt += "<option value=''>Please Choose Locaility</option>"
                $.each(data['locations'], function(index,location){
                    let locationName = location['name'];
                    let elemId = locationName.replace(/\ /g, '_');
                    // console.log(elemId);
                    newOpt += "<option value='"+location['id']+"'>"+location['name']+"</option>"

                });

                $("#locations").append(newOpt);
            }
        });
    }
}

function validateStoreCode(elem, action){
    let storecode = elem.value;
    let countryId = $("#countries").val();
    let countryName = $("#countries option:selected").html();
    countryName = countryName.replace(/&amp;/g, '&');
    if(storecode.length > 2){
        let base_url = window.location.origin;

        if (countryId != '' && storecode != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: base_url+"/admin/storecode/"+storecode+"/country/"+countryId,
                dataType: "json",
                beforeSend: function(){
                },
                success: function(data){
                    if(data['stcode_exists']) {
                        if(data['record_id'] && data['record_id'] != action){
                            let exception = '';
                            exception += "Store Code "+storecode+" already exists in "+countryName+".";
                            $("#stcodeval-msg").html(exception);
                            $("#stcodeval").show();

                            elem.value = '';
                        }else{
                            $("#stcodeval").hide();
                        }
                    }else{
                        $("#stcodeval").hide();
                    }
                }
            });
        }
    }
}