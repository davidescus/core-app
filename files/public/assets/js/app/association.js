/*
*  This method will retrive events info
*  object args {table}
*  will retribe like tipsters, leagues
*/
function getEventsInfo(args) {

    $.ajax({
        url: config.coreUrl + "/event/info?" + $.param(args),
        type: "get",
        //        dataType: "json",
        //        data: {},
        //        beforeSend: function() {},
        success: function (response) {

            console.log(response);

            var table = $('#table-association-' + args.table);

            // autocomplete tipster select
            var template = table.find('#template-select-tipster').html();
            // compile it with Template7
            var compiledTemplate = Template7.compile(template);
            // Now we may render our compiled template by passing required context
            var html = compiledTemplate(response);
            table.find('.select-tipster').html(html);

            // autocomplete league select
            var template = table.find('#template-select-league').html();
            // compile it with Template7
            var compiledTemplate = Template7.compile(template);
            // Now we may render our compiled template by passing required context
            var html = compiledTemplate(response);
            table.find('.select-league').html(html);

        },
        error: function () {}
    });
}
