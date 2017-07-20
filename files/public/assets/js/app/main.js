$(document).ready(function() {

    // app start flow
    setActivePage();

    $('#left-menu li').on('click', function() {
        // set active page in config
        config.activePage = $(this).attr('target');
        setActivePage();
    });

    /*
     * Make visible active page and trigger setControlFlow()
     * te retrive json with data for current page
     */
    function setActivePage() {

        // add class active for left mennu
        $('#left-menu li').removeClass('active');
        $('#left-menu li[target="' + config.activePage + '"]').addClass('active');

        // show content of desired page
        $('.page-container').addClass('hidden');
        $('.page-container.' + config.activePage).removeClass('hidden');

        // trigger setControlFlow() method
        setControlFlow();
    }

    /*
     * This function will controll methods
     * executed by specific page
     */
    function setControlFlow() {

        if (config.activePage == 'association') {
            getEventsInfo({
                table:"all",
                tipster:"",
                league:"",
                oddMin:"",
                oddMax:"",
            });
        }
    }
});
