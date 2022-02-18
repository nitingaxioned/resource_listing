let data_paged = 1;
let cat_iam = '';
let cat_looking = '';
let ajax_status = false;
jQuery(document).ready(function () {
    jQuery('.show_more').hide();
    jQuery('.show_less').hide();
    filterResources(cat_iam, cat_looking, data_paged);

    jQuery(".find").click(function() {
        if (!ajax_status) {
            data_paged = 1;
            cat_iam = jQuery('.cat_iam').val();
            cat_looking = jQuery('.cat_looking').val();
            filterResources(cat_iam, cat_looking, data_paged);
        }
    });

    jQuery('.show_more').click(function () {
        data_paged++;
        jQuery('.show_more').hide();
        filterResources(cat_iam, cat_looking, data_paged);
    });

    jQuery('.show_less').click(function () {
        if( !ajax_status ) {
            data_paged = 1;
            jQuery('.load_more').show();
            let n = jQuery('.list-resource li').length;
            n = n - 9;
            for (let j = 0; j < n; j++) {
                jQuery('.list-resource').children().last().remove();
            }
            jQuery('.show_less').hide();
        }
    });
});

function filterResources(id_iam, id_looking, paged) {
    jQuery('.show_less').hide();
    jQuery('.show_more').hide();
    paged == 1 ? jQuery('.list-resource').html("<li><h5>Loading....</h5></li>") : jQuery('.list-resource').append("<li><h5>Loading....</h5></li>");
    ajax_status = true;
    jQuery.ajax({
        url : ajax_object.ajax_url,
        data : {
            action : ajax_object.hook,
            id_iam : id_iam,
            id_looking : id_looking,
            paged : paged,
        },
        type : 'post',
        success : function(data) {
            paged == 1 && jQuery('.list-resource').html(data);
            if (paged > 1) {
                jQuery('.list-resource').children().last().remove();
                jQuery('.list-resource').append(data);
            }
            ajax_status = false;
        },
        error : function(errorThrown){
            alert(errorThrown);
            jQuery('.list-resource').html("<li><h5>Something went Wrong.</h5><p>Please try again...</p> </li>");
        }
    });
}