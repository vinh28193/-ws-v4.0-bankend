
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
$('.style-ull li.li-item-cate').mouseenter(function(){
    var $element = this;
    window.mytimeout = setTimeout(function(){
        $(".style-ull li.li-item-cate a.title-cate").removeClass('a-hover');
        $(".style-ull li.li-item-cate div.sub-menu-2").removeClass('sub-menu-2-hover');
        $(".style-ull li.li-item-cate").removeAttr('style');
        $($element).css('background','#fff');
        $($element).find("a.title-cate").addClass('a-hover');
        $($element).find("div.sub-menu-2").addClass('sub-menu-2-hover');
    }, 200);
});
$('.style-ull li.li-item-cate').mouseleave(function(){
    clearTimeout(window.mytimeout);
});
$('#menu1>li[role=presentation]').click(function () {
    var $elm = this;
    $("#menu1>li[role=presentation] ul.style-ull").each(function (k,v) {
        if($(v).attr('id') !== $($elm).find('ul.style-ull').attr('id') && $(v).css('display') !== 'none'){
            $(v).slideToggle('slow');
            $(v).parent().find('.ico-dropdown i').css('transform','scaleY(1)');
        }
    });
});
$('*[data-action=clickToLoad]').click(function () {
    ws.loading(true);
    window.location.assign($(this).attr('data-href'));
});
var autoHeightIframe = function (e) {
    console.log($(e));
    var he = $(e)[0].ownerDocument.body.clientHeight;
    $(e).css('height',he + 'px');
};
$('#dropAcount').click(function () {
    console.log($('.account-header-box.style-account').css('border'));
    if($('.account-header-box.style-account').attr('data-href') == 'hide' || !$('.account-header-box.style-account').attr('data-href')){
        $('.wait-main').css('display','block');
        $('.account-header-box.style-account').attr('data-href','show');
    }else {
        $('.wait-main').css('display','none');
        $('.account-header-box.style-account').attr('data-href','hide');
    }
});
$('.wait-main').click(function () {
    $('.wait-main').css('display','none');
    $('.account-header-box.style-account').attr('data-href','hide');
});