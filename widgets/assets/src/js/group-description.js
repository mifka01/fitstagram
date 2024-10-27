(function () {
  "use strict";

  function initializeDescriptions() {
    const translations = window.groupWidgetTranslations || {
      showMore: "Show more",
      showLess: "Show less",
    };

    document.querySelectorAll(".description-container").forEach((container) => {
      const description = container.querySelector(".description");
      const expandButton = container.querySelector(".expand-button");

      if (description && expandButton) {
        expandButton.textContent = translations.showMore;

        if (description.scrollHeight > description.clientHeight) {
          expandButton.classList.remove("hidden");
        }

        expandButton.addEventListener("click", () => {
          const isExpanded = description.classList.contains("line-clamp-none");
          description.classList.toggle("line-clamp-2", isExpanded);
          description.classList.toggle("line-clamp-none", !isExpanded);
          expandButton.textContent = isExpanded
            ? translations.showMore
            : translations.showLess;
        });
      }
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeDescriptions);
  } else {
    initializeDescriptions();
  }

  document.addEventListener("pjax:complete", initializeDescriptions);
})();
