function getEventsInfo(args) {

    /*
     *  create ajax request to get information
     */
    $.ajax({
        url: config.coreUrl + "/event/" + args.table + "/info";
        type: "post",
        dataType: "json",
        data: {},
        beforeSend: function() {};
        success: function (response) {

            console.log(response);
        },
        error: function () {}
    });
}
