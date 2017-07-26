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
 *  get events filtered by selection and launch modal
 */
$('.table-association').on('click', '.modal-get-event', function() {
    var parrentTable = $(this).parents('.table-association');

    getAvailableEvents({
        table: parrentTable.attr('data-table'),
        provider: parrentTable.find('.select-provider').val(),
        league: parrentTable.find('.select-league').val(),
        minOdd: parrentTable.find('.select-minOdd').val(),
        maxOdd: parrentTable.find('.select-maxOdd').val()
    });
});

/*
 *  Check event on modal row click
 */
$('#modal-available-events').on('click', '.event', function() {
    var c = $(this).find('.use');
    (c.is(':checked')) ?  c.prop('checked', false) : c.prop('checked', true);
});

/*
 * Button click for import events
 */
$('#modal-available-events').on('click', '.import', function() {
    // get events ids for association
    var ids = [];
    $('#modal-available-events .use:checked').each(function() {
        ids.push($(this).attr('data-id'));
    });

    //    if($.isEmptyObject(ids)) {
    //        alert("You must select at least one event");
    //        return;
    //    }

    // getSystemDate
    $.ajax({
        url: config.coreUrl + "/association",
        type: "post",
        dataType: "json",
        data: {
            eventsIds: ids,
            table : $('#modal-available-events .table-identifier').val(),
            systemDate: $('#modal-available-events .system-date').val(),
        },
        beforeSend: function() {},
        success: function (response) {

            console.log(response);
            alert("Type: --- " + response.type + " --- \r\n" + response.message);

        },
        error: function () {}
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
        success: function (response) {

            var element = $('#table-association-' + args.table);

            var template = element.find('.template-events-number').html();
            var compiledTemplate = Template7.compile(template);
            var html = compiledTemplate(response);
            element.find('.events-number').html(html);

        },
        error: function () {}
    });
}

/*
* this function will retribe available events based on selection
* object args: table, provider, minOdd, maxOdd
*/
function getAvailableEvents(args) {

    $.ajax({
        url: config.coreUrl + "/event/events?" + $.param(args),
        type: "get",
        success: function (response) {

            var element = $('#modal-available-events');
            var data = {
                table: args.table,
                events: response.events,
            };

            var template = element.find('.template-modal-content').html();
            var compiledTemplate = Template7.compile(template);
            var html = compiledTemplate(data);
            element.find('.modal-content').html(html);

            element.modal();
        },
        error: function () {}
    });
}
