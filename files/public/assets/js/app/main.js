var config = {
    coreUrl: "http://51.15.78.71/admin",
    activePage: "association"
};

// app start flow
setActivePage();

$('#left-menu li').on('click', function() {
    // set active page in config
    config.activePage = $(this).attr('target');
    setActivePage();
});

function setActivePage() {

    // add class active for left mennu
    $('#left-menu li').removeClass('active');
    $('#left-menu li[target="' + config.activePage + '"]').addClass('active');

    // show content of desired page
    $('.page-container').addClass('hidden');
    $('.page-container.' + config.activePage).removeClass('hidden');
}
