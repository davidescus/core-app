/*
 * Provisory function to get all events for archives
 */
function getAllEventsForArchivesBig() {
    $.ajax({
        url: config.coreUrl + "/archive",
        type: "get",
        success: function (response) {

            var element = $('#container-archive-big');

            var template = element.find('.template-table-content').html();
            var compiledTemplate = Template7.compile(template);
            var html = compiledTemplate(response);
            element.find('.table-content').html(html);

        },
        error: function () {}
    });
}
