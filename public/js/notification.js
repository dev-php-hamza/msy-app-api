function clearNotificationObj(elem){
    let countryId = elem.value;
    if (countryId != '') {
        $('#selection').removeAttr('disabled');
        $('#choose-one').removeAttr('disabled');
        $('#choose-one').empty();
        $('#btnSubmit').removeAttr('disabled');
    }else{
        $('#choose-one').empty();
    }
    return false;
}

function getNotificationType(elem){
    let notificationType = elem.value;
    let countryId = $('#countryId').val();
    let base_url = window.location.origin;
    if ( notificationType!= '' && notificationType != 'text') {
        $('#text-title').hide();
        $('#text-title input').removeAttr('required');
        $('#choose-one').empty();
        $('#choose-one').prop('required',true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

            $.ajax({
                type: 'get',
                url: base_url+"/admin/notifications/"+countryId+"/"+notificationType+"",
                dataType: "json",
                beforeSend: function(){
                },
                success: function(data){
                    let newOpt = '';
                    newOpt += "<option value=''>Please Choose "+notificationType+"</option>"
                    $.each(data, function(index,value){
                        newOpt += "<option value='"+value['id']+"'>"+value['title']+"</option>"

                    });

                    $('#select-type').show();
                    $("#choose-one").append(newOpt);
                }
            });
    }
    else{
        // $('#choose-one').hide();
        $('#choose-one').prop('required',false);
        $('#text-title').show();
        $('#text-title input').attr('required', 'required');
        $('#select-type').hide();
    }
}
