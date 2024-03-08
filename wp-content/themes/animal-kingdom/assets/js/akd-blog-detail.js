(function ($) {
  $(document).ready(function () {
    /**
     * ============================
     * Start JS for related blogs.
     * ============================
     */

    let blog_paged = 2;
    let blog_per_page = 3;

    // Initial check to hide the "Read more" button if there are no more blogs.
    let blog_track = $(".related-list-wrapper").data("related-blogs");
    if (blog_track <= blog_per_page) {
      $(".load-more-related-blogs").hide();
    }

    // Click event on load more button.
    $(".load-more-related-blogs").on("click", function (e) {
      e.preventDefault();
      loadMoreRelatedBlogs();
    });

    /**
     * Function make an AJAX call to load more related blogs.
     *
     * @return {void}
     */
    function loadMoreRelatedBlogs() {
      $(".load-more-related-blogs").hide();
      $(".related-blogs-loader").show();
      let post_id = $(".related-list-wrapper").attr("data-post-id");

      $.ajax({
        url: blogDetailScript.ajax_url,
        type: "POST",
        data: {
          action: "akd_load_more_related_blogs",
          nonce: blogDetailScript.nonce,
          post_id: post_id,
          blog_paged: blog_paged,
          blog_per_page: blog_per_page,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".related-blog-list").append(htmlresponse);
            blog_paged++;
            // Check if there are more blogs.
            if (
              $(".related-blog-list .list").length >=
              $(".related-list-wrapper").attr("data-related-blogs")
            ) {
              $(".load-more-related-blogs").hide();
            } else {
              $(".load-more-related-blogs").show();
            }
          }
        },
        complete: function () {
          $(".related-blogs-loader").hide();
        },
      });
    }

    /**
     * ============================
     * End JS for related blogs.
     * ============================
     */
  });
})(jQuery);
