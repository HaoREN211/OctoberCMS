/**
 * initializeSorting()
 *
 * This sets up our list to interact with html5sortable. It is called
 * on page load, and after the list is refreshed.
 */
function initializeSorting() {

    // Add a sortable class to our table so our css can easily distinguish
    $('.list-widget').addClass('html5sortable');

    // Add a "disabled" class to rows that should not be sorted
    $('.list-widget tr:has(.disabled)').addClass('disabled');

    // Initialize HTML5Sortable on our tbody
    $('.list-widget tbody').html5sortable({
        forcePlaceholderSize: true,
        items: ':not(.disabled)'
    });

    // Callback function for when form is saved
    $('button[data-request="onSave"]').on('click', function () {

        // Retrieve relation items order
        var order = $('.position').map(function () {
            return parseInt(this.textContent);
        }).get();

        // Inject order in form model request data
        var requestData = $(this).data('request-data') + ', checked: [' + order.toString() + ']';

        // Update request data and data-request-success
        $(this).data('request-data', requestData);
        $(this).data('request-success', 'initializeSorting();');
    });

    $(window).on('ajaxSuccess', function (event, context, data) {

        var handlers = [
            'onRelationManagePivotCreate',
            'onRelationManagePivotUpdate',
            'onRelationManageAdd',
            'onRelationButtonRemove',
            'onRelationButtonDelete'
        ];

        if (typeof data.context != 'undefined') {
            if (handlers.indexOf(data.context.handler) > -1) {
                initializeSorting();
            }
        }
    });
}

$(function () {
    initializeSorting();
});