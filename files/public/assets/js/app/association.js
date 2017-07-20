/*
*  This method will retrive events info
*  object args {
*               table
*               tipster
*               league
*               oddMin
*               oddMax
*               }
*/
function getEventsInfo(args) {

    $.ajax({
        url: config.coreUrl + "/event/" + args.table + "/info",
        type: "post",
        dataType: "json",
        data: {},
        beforeSend: function() {},
        success: function (response) {

            console.log(response);
        },
        error: function () {}
    });
}
