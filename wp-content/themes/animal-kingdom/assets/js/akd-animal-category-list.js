(function ($) {
  $(document).ready(function () {
    // Function to clear all query parameters from the URL
    function clearQueryString() {
      if (history.replaceState) {
        history.replaceState({}, document.title, window.location.pathname);
      }
    }

    /**
     * ====================================
     * Start JS for animal category listing.
     * ====================================
     */

    let paged = 2;
    let per_page = 12;

    // Initial check to hide the "Read more" button if there are no more animals.
    let animal_track = $(".category-listing-card-wrapper").data("animal-track");
    if (animal_track <= per_page) {
      $(".load-more-animal-category").hide();
    }

    // Click event on load more button.
    $(".load-more-animal-category").on("click", function (e) {
      e.preventDefault();
      loadMoreAnimalsByCategory();
    });

    // Click on animal category.
    $(".animal-category-list").on("click", function () {
      clearQueryString();
      let category_id = $(this).data("term-id");
      let search_term = $("#animal-category-search").val();
      $(".animal-category-list").removeClass("active");
      $(this).addClass("active");
      get_animals_by_category(category_id, search_term);
    });

    // Click on sorting button.
    $(".sorting-btn").on("click", function () {
      let sort_value = $(this).attr("data-sort");
      let category_id = $(".animal-category-list.active").attr("data-term-id");
      let search_term = $("#animal-category-search").val();
      get_animals_by_category(category_id, search_term, sort_value);
    });

    // Search on animals.
    $("#animal-category-search").on("keyup", function (e) {
      if (e.key === "Enter") {
        let search_term = $(this).val();
        let category_id = $(".animal-category-list.active").attr(
          "data-term-id"
        );
        get_animals_by_category(category_id, search_term);
      }
    });

    /**
     * Function make an AJAX call to filter animals by category.
     *
     * @param {integer} category_id Category ID
     * @param {string} search_term Category Value
     * @param {string} sort_value Sort Value
     *
     * @return {void}
     */
    function get_animals_by_category(category_id, search_term, sort_value) {
      $(".category-listing-card-wrapper").html("");
      $(".animal-category-loader").show();
      $(".load-more-animal-category").hide();
      $.ajax({
        url: animalCatScript.ajax_url,
        type: "POST",
        data: {
          action: "akd_get_animals_by_category",
          nonce: animalCatScript.nonce,
          category_id: category_id,
          per_page: per_page,
          search_term: search_term,
          sort_value: sort_value,
        },
        success: function (response) {
          let totalAnimals = response.data.total_animals;

          // Append category response.
          $(".category-listing-card-wrapper").html(response.data.html);

          // Append favorite content.
          $(".favorite-farm-animal-wrapper").html(response.data.fav_content);

          // Manage loadmore.
          $(".category-listing-card-wrapper").attr(
            "data-animal-track",
            totalAnimals
          );
          if (totalAnimals <= per_page) {
            $(".load-more-animal-category").hide();
          } else {
            $(".load-more-animal-category").show();
          }

          // Manage sorting.
          let sorting = $(".sorting-btn").attr("data-sort");
          if (sort_value !== undefined && sorting == "ASC") {
            $(".sorting-btn").attr("data-sort", "DESC");
            $(".sorting-btn").html("SORT Z TO A");
          } else if (sort_value !== undefined && sorting == "DESC") {
            $(".sorting-btn").attr("data-sort", "ASC");
            $(".sorting-btn").html("SORT A TO Z");
          } else {
            $(".sorting-btn").attr("data-sort", "DESC");
            $(".sorting-btn").html("SORT Z TO A");
          }
        },
        complete: function () {
          $(".animal-category-loader").hide();
          paged = 2;
        },
      });
    }

    /**
     * Function make an AJAX call to load more animals.
     *
     * @return {void}
     */
    function loadMoreAnimalsByCategory() {
      $(".load-more-animal-category").hide();
      $(".animal-category-loader").show();
      let category_id = $(".animal-category-list.active").attr("data-term-id");
      let search_term = $("#animal-category-search").val();
      let sort_value = $(".sorting-btn").attr("data-sort");
      sort_value == "ASC" ? (sort_value = "DESC") : (sort_value = "ASC");

      $.ajax({
        url: animalCatScript.ajax_url,
        type: "POST",
        data: {
          action: "akd_load_more_animals_by_category",
          nonce: animalCatScript.nonce,
          category_id: category_id,
          paged: paged,
          per_page: per_page,
          search_term: search_term,
          sort_value: sort_value,
        },
        success: function (response) {
          let htmlresponse = response.data.html;
          if (htmlresponse) {
            $(".category-listing-card-wrapper").append(htmlresponse);
            paged++;
            // Check if there are more animals.
            if (
              $(".category-listing-card-wrapper .category-listing-card-item")
                .length >=
              $(".category-listing-card-wrapper").attr("data-animal-track")
            ) {
              $(".load-more-animal-category").hide();
            } else {
              $(".load-more-animal-category").show();
            }
          }
        },
        complete: function () {
          $(".animal-category-loader").hide();
        },
      });
    }

    /**
     * ====================================
     * End JS for animal category listing.
     * ====================================
     */
  });
})(jQuery);
