var config = {
    coreUrl: "http://51.15.78.71/admin",
    activePage: "dashboard"
};

$('#left-menu li').on('click', function() {

    // add class active for left mennu
    $('#left-menu li').removeClass('active');
    $(this).addClass('active');

    // show content of desired page
    $('.page-container').addClass('hidden');
    $('.page-container.' + $(this).attr('target')).removeClass('hidden');
});
