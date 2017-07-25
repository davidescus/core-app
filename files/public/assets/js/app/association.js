/*
 *  Clickable acctions
 */

/*
* refresh provider, leagues and available events number
*/
$('.table-association').on('click', '.refresh-event-info', function() {
    var table = $(this).parents('.table-association').attr('data-table');

    getEventsInfo({ table: table });
    getAvailableEventsNumber({ table: table });
});

/*
 *  show available events when change provider, league, odds selection
 */
$('.table-association').on('change', '.select-provider, .select-league, .select-minOdd, .select-maxOdd', function() {
    var parrentTable = $(this).parents('.table-association');

    getAvailableEventsNumber({
        table: parrentTable.attr('data-table'),
        provider: parrentTable.find('.select-provider').val(),
        league: parrentTable.find('.select-league').val(),
        minOdd: parrentTable.find('.select-minOdd').val(),
        maxOdd: parrentTable.find('.select-maxOdd').val()
    });
});


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

            var table = $('#table-association-' + args.table);

            // autocomplete provider select
            var template = table.find('.template-select-provider').html();
            // compile it with Template7
            var compiledTemplate = Template7.compile(template);
            // Now we may render our compiled template by passing required context
            var html = compiledTemplate(response);
            table.find('.select-provider').html(html);

            // autocomplete league select
            var template = table.find('.template-select-league').html();
            // compile it with Template7
            var compiledTemplate = Template7.compile(template);
            // Now we may render our compiled template by passing required context
            var html = compiledTemplate(response);
            table.find('.select-league').html(html);

        },
        error: function () {}
    });
}

/*
* this function will retrive and show available events number
* object args: table, provider, minOdd, maxOdd
*/
function getAvailableEventsNumber(args) {

    $.ajax({
        url: config.coreUrl + "/event/number?" + $.param(args),
        type: "get",
        //        dataType: "json",
        //        data: {},
        //        beforeSend: function() {},
        success: function (response) {

            var table = $('#table-association-' + args.table);

            // autocomplete provider select
            var template = table.find('.template-events-number').html();
            // compile it with Template7
            var compiledTemplate = Template7.compile(template);
            // Now we may render our compiled template by passing required context
            var html = compiledTemplate(response);
            table.find('.events-number').html(html);

        },
        error: function () {}
    });
}
