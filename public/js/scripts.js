$(document).ready(function(){
    $(document).foundation();
    $(".datepicker").datepicker();
    $("form.form-data").submit(function(event){
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'JSON',
            success: function(data){
                if (data.error != '') {
                    pushAlert(data.error, 'alert');
                } else if (typeof onFormDataSuccess == 'function') {
                    onFormDataSuccess(data);
                }
            },
            error: function(data){
                pushAlert('Form processing has been halted.', 'alert');
            },
            beforeSend: function(){
                $('form.form-data').find('button').last().html('Processing...').attr('disabled', true);
            },
            complete: function(){
                $('form.form-data').find('button').last().html('Submit').removeAttr('disabled');
            }
        });
    });
});

function pushAlert(msg, space)
{
    var html = '<div data-alert class ="alert-box radius ' + space + '">' + msg + '<a href="#" class="close">&times;</a> </div>';
    $(".alert-aware").prepend(html);
}

function pushModal(title, msg)
{
    $("#main-modal-header").html(title);
    $("#main-modal-content").html(msg);
    $("#main-modal").foundation('reveal', 'open');
}

