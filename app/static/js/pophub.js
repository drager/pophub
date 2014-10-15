$(function() {
  $('time').each(function(i, e) {
    var time = $(e).attr('datetime');

    console.log(time);

    $(e).html('<span>' + moment(time).fromNow() + '</span>');
  });
})