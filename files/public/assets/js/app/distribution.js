/*
 * Clickable events
 */
// seelect / deselect all events from a site
$('#container-distributed-events').on('change', '.select-group-site', function() {
    if ($(this).is(':checked'))
        $(this).closest('.row').find('.use').prop('checked', true);
    else
        $(this).closest('.row').find('.use').prop('checked', false);
});

/*
 * Manual publish events in archive
 */
$('#container-distributed-events').on('click', '.action-publish', function() {
    var data = [];

    // get events ids for association
    $('#container-distributed-events .use:checked').each(function() {
        var parentPackageId = $(this).closest('.package-row').attr('data-id');
        data.push({
            packageId: parentPackageId,
            eventId: $(this).attr('data-id'),
        });
    });

    $.ajax({
        url: config.coreUrl + "/archive",
        type: "post",
        dataType: "json",
        data: {
            data: data,
        },
        success: function (response) {
            console.log(response);
        },
        error: function () {}
    });
});


/*
 * This function will complete distribution table
 * with all distributed events
 */
function getDistributedEvents() {
    $.ajax({
        url: config.coreUrl + "/distribution",
        type: "get",
        success: function (response) {

            var data = response;

            var element = $('#container-distributed-events');

            var template = element.find('.template-table-content').html();
            var compiledTemplate = Template7.compile(template);
            var html = compiledTemplate(data);
            element.find('.table-content').html(html);
        },
        error: function () {}
    });
}
