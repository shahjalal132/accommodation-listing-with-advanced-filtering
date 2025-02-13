(function ($) {
  $(document).ready(function () {
    // Code here

    $(".filter-checkbox").on("change", function () {
      // initialize filters
      let filters = [];

      // Collect selected filters
      $(".filter-checkbox:checked").each(function () {
        let value = $(this).val();

        filters.push(value);
      });

      // AJAX request
      $.ajax({
        url: wpb_public_localize.ajax_url,
        type: "POST",
        data: {
          action: "filter_accommodations",
          filters: filters,
        },
        beforeSend: function () {
          $("#accommodations-wrapper").html("<p>Loading...</p>");
        },
        success: function (response) {
          // render accommodations
          $("#accommodations-wrapper").html(response);
        },
      });
    });
  });
})(jQuery);
