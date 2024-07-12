$(document).ready(function() {
    $(window).on('beforeunload', function() {
        $('#loader').show();
    });
});

$(window).on('load', function() {
    $('#loader').hide();
});