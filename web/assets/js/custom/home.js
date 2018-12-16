$(document).ready(function(){

    var ias = jQuery.ias({
        container: '.timeline .box-content',
        item: '.publication-item',
        pagination: '.timeline .pagination',
        next: '.timeline .pagination .next_link',
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
        text: 'There is no more publication'
    }));

    ias.on('ready', function(event){
        buttons();
    });

    ias.on('rendered', function(event){
        buttons();
    });


    function buttons()
    {
        // $('[data-toogle="tooltip"]').tooltip();
        $('[data-toggle="tooltip"]').tooltip();

        $(".btn-img").unbind("click").click(function()
        {
            $(this).parent().find('.pub-image').fadeToggle();
        });

        $(".btn-delete-pub").unbind('click').click(function(){
            $(this).parent().parent().addClass('hidden');

            $.ajax({
                url: URL+'/publication/remove/'+$(this).attr("data-id"),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                }
            });
        });

        
    }
});
