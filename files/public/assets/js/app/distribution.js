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

            console.log(data);

            var element = $('#container-distributed-events');

            var template = element.find('.template-table-content').html();
            var compiledTemplate = Template7.compile(template);
            var html = compiledTemplate(data);
            element.find('.table-content').html(html);
        },
        error: function () {}
    });
}
