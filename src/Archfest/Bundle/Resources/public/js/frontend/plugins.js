$(document).ready(function () {
    var hoverDiv = null;
    $('.flash-greed').on('mouseenter', function (e) {
        var div = $(this);
        div.css('display', 'none');
        hoverDiv = div;
    })
    $('object').on('mouseleave', function (e) {
        hoverDiv.css('display', '');
    })
});