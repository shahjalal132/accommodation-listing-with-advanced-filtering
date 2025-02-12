(function ($) {
  $(document).ready(function () {
    // Code here

    $(".filter-checkbox").on("change", function () {
      let filters = {};

      // Collect selected filters
      $(".filter-checkbox:checked").each(function () {
        let name = $(this).attr("name");
        let value = $(this).val();

        if (!filters[name]) {
          filters[name] = [];
        }
        filters[name].push(value);
      });

      console.log(filters);

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
          console.log(response);
          $("#accommodations-wrapper").html(response);
        },
      });
    });
  });
})(jQuery);
