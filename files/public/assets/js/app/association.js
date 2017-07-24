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
        },
        error: function () {}
    });
}
