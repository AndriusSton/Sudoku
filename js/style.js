$('document').ready(function () {
    $('.level-btn').on('click', function () {
        console.log('clicked');
        $('#controls').toggleClass('.filled');
    })
});



