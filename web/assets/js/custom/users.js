$(document).ready(function(){

    var ias = jQuery.ias({
        container: '.box-users',
        item: '.user-item',
        pagination: '.pagination',
        next: '.pagination .next_link',
        triggerPageThreshold: 5
    });

    ias.extension(new IASTriggerExtension({
        text: 'See more',
        offset: 3
    }));

    ias.extension(new IASSpinnerExtension({
        src: URL+'/../../assets/images/ajax-loader.gif'
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'There is no more person'
    }));

    ias.on('ready', function(event){
        followButtons();
    });

    ias.on('rendered', function(event){
        followButtons();
    });


    function followButtons() {
        $(".btn-follow").unbind("click").click(function() {
            $(this).addClass("hidden");
            $(this).parent().find(".btn-unfollow").removeClass("hidden");

            $.ajax({
                url: 'http://localhost:8888/CursoSocialNetwork/web/app_dev.php/follow',
                //url: URL+'/follow',
                type: 'POST',
                data: {followed: $(this).attr("data-followed")},
                success: function (response) {
                    console.log(response);
                }
            });
        });

        $(".btn-unfollow").unbind("click").click(function() {
            $(this).addClass("hidden");
            $(this).parent().find(".btn-follow").removeClass("hidden");

            $.ajax({
                url: 'http://localhost:8888/CursoSocialNetwork/web/app_dev.php/unfollow',
                //url: URL+'/unfollow',
                type: 'POST',
                data: {followed: $(this).attr("data-followed")},
                success: function (response) {
                    console.log(response);
                }
            });
        });
    }
});
