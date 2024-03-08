(function ($) {
  $(document).ready(function () {
    /**
     * ================================
     * Start JS for search listing.
     * ================================
     */

    let page = 2; // Initialize the page number.
    let perPage = 6; // Number of blogs to load per request.

    // Initial check to hide the "Load more" button if there are no more blogs.
    let blog_track = parseInt($(".akd-search-listing").data("blog-track"));
    if (blog_track <= perPage) {
      $(".load-more-search").hide();
    }

    // Load More button click event.
    $(".load-more-search").on("click", function (e) {
      e.preventDefault();
      loadMoreSearchResults();
    });

    /**
     * Function make an AJAX call to load more blogs.
     *
     * @return {void}
     */
    function loadMoreSearchResults() {
      $(".load-more-search").hide();
      $(".search-result-loader").show();
      let start_index = parseInt($(".akd-search-listing").attr("data-index"));
      let search_value = $(".akd-search-listing").attr("data-value");

      $.ajax({
        url: searchScript.ajax_url,
        type: "POST",
        data: {
          action: "akd_load_more_search_results",
          nonce: searchScript.nonce,
          start_index: start_index,
          search_value: search_value,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".akd-search-listing").append(htmlresponse);
            $(".akd-search-listing").attr("data-index", start_index + 6);
            if (
              $(".list").length >=
              $(".akd-search-listing").attr("data-blog-track")
            ) {
              $(".load-more-search").hide();
            } else {
              $(".load-more-search").show();
            }
          }
        },
        complete: function () {
          $(".search-result-loader").hide();
        },
      });
    }

    /**
     * ================================
     * End JS for search listing.
     * ================================
     */
  });
})(jQuery);
