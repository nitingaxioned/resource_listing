let data_paged = 1; // to set paged value
let cat_iam = ''; // default value for catogory filter
let cat_looking = ''; // default value for catogory filter
let ajax_status = false;
let count = 0; // no of post in list.
jQuery(document).ready(function () {
    jQuery('.show_more').hide();
    jQuery('.show_less').hide();
    filterResources(cat_iam, cat_looking, data_paged); // default first time load

    // click functionality for explore btn
    jQuery(".find").click(function() {
        if (!ajax_status) {
            data_paged = 1;
            cat_iam = jQuery('.cat_iam').val();
            cat_looking = jQuery('.cat_looking').val();
            filterResources(cat_iam, cat_looking, data_paged);
        }
    });

    // click functionality for loadmore btn
    jQuery('.show_more').click(function () {
        count = jQuery('.list-resource li').length;
        data_paged++;
        jQuery('.show_more').hide();
        filterResources(cat_iam, cat_looking, data_paged);
    });

    // click functionality for showless btn
    jQuery('.show_less').click(function () {
        if( !ajax_status ) {
            data_paged = 1;
            jQuery('.show_more').show();
            let n = jQuery('.list-resource li').length;
            n = n - 9;
            for (let j = 0; j < n; j++) {
                jQuery('.list-resource').children().last().remove();
            }
            jQuery('.show_less').hide();
        }
    });
});

// function to make ajax call
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
            setLoadMoreBtn();
            paged > 1 && jQuery('.show_less').show();
        },
        error : function(errorThrown){
            alert(errorThrown);
            jQuery('.list-resource').html("<li><h5>Something went Wrong.</h5><p>Please try again...</p> </li>");
        }
    });
}

// to handle visibility of load more btn
function setLoadMoreBtn(){
    let temp = jQuery('.list-resource').children().last().hasClass("hide-me");
    temp ? jQuery('.show_more').hide() : jQuery('.show_more').show();
}