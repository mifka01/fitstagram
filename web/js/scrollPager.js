(function ($) {
  "use strict";

  const DEFAULT_OPTIONS = {
    pageParam: "page",
    indicator: "#loading-indicator",
    containerSelector: "[data-key]",
    summarySelector: ".summary-end",
    errorCallback: null,
  };

  class ScrollPager {
    constructor(element, options, callback) {
      this.element = $(element);
      this.options = $.extend({}, DEFAULT_OPTIONS, options);
      this.callback = callback || function () {};
      this.page = 1;
      this.isLoading = false;

      this.init();
    }

    init() {
      this.element.on("click", (e) => {
        e.preventDefault();
        if (!this.isLoading) {
          this.loadNextPage();
        }
      });
    }

    loadNextPage() {
      if (this.page >= this.options.pageCount) {
        this.element.remove();
        return;
      }

      this.isLoading = true;

      const url = new URL(document.location);
      url.searchParams.set(this.options.pageParam, ++this.page);

      $.ajax({
        url: url.href,
        dataType: "html",
        method: "GET",
        timeout: 10000, // 10 second timeout
      })
        .done((data) => this.handleSuccess(data))
        .fail((jqXHR, textStatus, errorThrown) =>
          this.handleError(jqXHR, textStatus, errorThrown),
        )
        .always(() => this.handleComplete());
    }

    handleSuccess(data) {
      try {
        const $data = $(data);
        const $list = this.element.parent();
        const $newItems = $data
          .find(`#${this.element.attr("id")}`)
          .parent()
          .find('> ' + this.options.containerSelector);

        if ($newItems.length === 0) {
          throw new Error("No new items found in response");
        }

        $list
          .find('> ' + this.options.containerSelector)
          .last()
          .after($newItems);

        this.updateSummary($list);

        if (this.page >= this.options.pageCount) {
          this.element.fadeOut(400, () => this.element.remove());
        }
      } catch (error) {
        console.error("Error processing response:", error);
        this.handleError(null, "parsererror", error);
      }
    }

    handleError(jqXHR, textStatus, errorThrown) {
      console.error("Ajax request failed:", textStatus, errorThrown);

      if (typeof this.options.errorCallback === "function") {
        this.options.errorCallback(textStatus, errorThrown);
      }

      // Revert page number on error
      this.page--;
    }

    handleComplete() {
      this.isLoading = false;
      this.callback();
    }

    updateSummary($list) {
      const $summary = $list.find(this.options.summarySelector);
      if ($summary.length) {
        const itemCount = $list.find(
          '> ' + this.options.containerSelector
        ).length;
        $summary.text(itemCount);
      }
    }
  }

  $.fn.scrollpager = function (options, callback) {
    return this.each(function () {
      if (!$.data(this, "scrollpager")) {
        $.data(this, "scrollpager", new ScrollPager(this, options, callback));
      }
    });
  };
})(jQuery);
