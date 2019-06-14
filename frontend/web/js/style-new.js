
$('.dropdown').on('show.bs.dropdown', function(e){
    $(this).children('a').css('color','#141c2e');
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
});

$('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).children('a').css('color','#2b97b7');
    $(this).find('.dropdown-menu').first().css('top','12px').stop(true, true).slideUp(200);
});
var showTab = function (id) {
  $('#'+id).addClass('open');
};
var hideTab = function (id) {
    $('#'+id).removeClass('open');
};
$('#menuAccount .social-button a.btn').click(function () {
    ws.loading(true);
});