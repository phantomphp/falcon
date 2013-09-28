$(document).ready(function(){

    $(document).foundation();

    $(".datepicker").datepicker();

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
