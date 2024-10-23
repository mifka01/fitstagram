(function ($) {
  "use strict";
  $.fn.scrollpager = function (options, callback) {
    return this.each(function () {
      this.options = options;
      this.page = 1;

      $(this).click(function (e) {
        e.preventDefault();

        let url = new URL(document.location);
        url.searchParams.set(this.options.pageParam, ++this.page);

        let that = this;

        $(this.options.indicator).addClass("show");

        $.ajax({
          url: url.href,
          dataType: "html",
          error: function (x, s, err) {
            console.log(err);
          },
          success: function (data, s, x) {
            let loader = $(e.target),
              list = loader.parent();

            list
              .find("[data-key]")
              .last()
              .after(
                $(data)
                  .find("#" + e.target.id)
                  .parent()
                  .find("[data-key]"),
              );
            list.find(".summary-end").text(list.find("[data-key]").length);

            if (that.page >= that.options.pageCount) {
              loader.remove();
            }
          },
          complete: function (x, s) {
            $(that.options.indicator).removeClass("show");
            callback();
          },
        });
      });
      return this;
    });
  };
})(jQuery);
