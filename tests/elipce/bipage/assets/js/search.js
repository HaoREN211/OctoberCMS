$(document).ready(function () {
    $('#search').submit(function (e) {
        e.preventDefault();
        // Empty page content and toggle results div
        $('#results').parent()
            .slideToggle(300)
            .empty()
            .append('<div id="results"></div>')
            .slideToggle(300);
    });
});