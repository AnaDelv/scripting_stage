$(document).ready(function() {

    $('.button').on('change', function() {
        $('.button').not(this).prop('checked', false);
    });

})