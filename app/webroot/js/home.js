// <!-- Once the page is loaded, initalize the plug-in. -->
var wookmark;
(function() {

    // fix position content div
    setTimeout(function(){
        jQuery('#my-content').css({paddingTop : jQuery('#my-header > .container-fluid').outerHeight(true)});

        imagesLoaded('#wc', function() {
            wookmark = new Wookmark('#wc', {
                itemWidth: '50%', // Optional min width of a grid item
                flexibleWidth: '50%',
                //outerOffset: 0, // Optional the distance from grid to parent
                offset : 0,
                fillEmptySpace: true
            });
        });
    }, 1000);

    window.addEventListener('resize', function() {
        jQuery('#my-content').css({paddingTop : jQuery('#my-header > .container-fluid').outerHeight(true)});
    }, true);

})();