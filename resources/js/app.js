import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

// Sidebar toggle functionality
document.addEventListener("DOMContentLoaded", function () {
  const sidebarToggle = document.getElementById("sidebarToggle");
  const desktopSidebarToggle = document.getElementById("desktopSidebarToggle");
  const sidebar = document.getElementById("sidebar");
  const sidebarOverlay = document.getElementById("sidebarOverlay");

  // Clear any problematic localStorage on initial load to prevent unwanted sidebar collapse
  if (
    window.location.pathname.includes("siswa/ujian") &&
    !localStorage.getItem("sidebarManuallyCollapsed")
  ) {
    localStorage.removeItem("sidebarCollapsed");
  }

  // Check if sidebar collapse is temporarily disabled
  const tempDisabled = localStorage.getItem("tempDisableSidebarCollapse");

  // Mobile sidebar toggle
  if (sidebarToggle && sidebar && sidebarOverlay) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("-translate-x-full");
      sidebarOverlay.classList.toggle("hidden");
    });

    sidebarOverlay.addEventListener("click", function () {
      sidebar.classList.add("-translate-x-full");
      sidebarOverlay.classList.add("hidden");
    });
  }

  // Desktop sidebar toggle (collapse/expand)
  if (desktopSidebarToggle && sidebar) {
    desktopSidebarToggle.addEventListener("click", function () {
      // Don't allow collapse if temporarily disabled
      if (tempDisabled === "true") {
        return;
      }

      sidebar.classList.toggle("sidebar-collapsed");

      // Save state to localStorage
      const isCollapsed = sidebar.classList.contains("sidebar-collapsed");
      localStorage.setItem("sidebarCollapsed", isCollapsed);
      localStorage.setItem("sidebarManuallyCollapsed", "true");

      // Update header dropdown positioning when sidebar toggles
      setTimeout(() => {
        // Trigger Alpine re-render for dropdown positioning
        if (window.Alpine) {
          const dropdownElement = document.querySelector('[x-data*="open"]');
          if (dropdownElement && dropdownElement._x_dataStack) {
            // Force Alpine to re-evaluate
            Alpine.initTree(dropdownElement);
          }
        }
      }, 300);
    });

    // Only restore sidebar state if user manually collapsed it and not temporarily disabled
    const savedState = localStorage.getItem("sidebarCollapsed");
    const manuallyCollapsed = localStorage.getItem("sidebarManuallyCollapsed");

    if (
      savedState === "true" &&
      manuallyCollapsed === "true" &&
      tempDisabled !== "true"
    ) {
      sidebar.classList.add("sidebar-collapsed");
    }
  }

  // Ensure profile dropdown is always functional
  const profileButton = document.querySelector("[x-data] button");
  if (profileButton) {
    // Add fallback click handler if Alpine fails
    profileButton.addEventListener("click", function (e) {
      const dropdown = this.parentElement.querySelector("[x-show]");
      if (dropdown) {
        const isHidden =
          dropdown.style.display === "none" || !dropdown.style.display;
        dropdown.style.display = isHidden ? "block" : "none";
      }
    });
  }

  // Preloader
  const preloader = document.getElementById("preloader");
  if (preloader) {
    setTimeout(() => {
      preloader.style.display = "none";
    }, 500);
  }
});
