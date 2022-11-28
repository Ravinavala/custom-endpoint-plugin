function getUserInfo(userId) {
    console.log(inspyde_user_listing_ajax);
    jQuery.ajax({
        type: 'post',
        url: inspyde_user_listing_ajax.ajaxurl,
        data: {action: 'fetch_user_details', security: inspyde_user_listing_ajax.nonce, userid: userId},
        success: function (data) {
            console.log(data);
            jQuery('.user_detail_block').html(data);
            return false;
        }
    });
}

jQuery('.user_row a').click(function () {
    var userId = jQuery(this).attr('data-userid');
    getUserInfo(userId);
});

//called pagination
jQuery(document).on('click', '.custom_pagination a', function (e) {
    e.preventDefault();
    var paged = $(this).data('page_id');
    myFunction(paged);
});

