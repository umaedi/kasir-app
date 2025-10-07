/**
 * Main
 */

"use strict";

let menu, animate;

(function () {
  // Initialize menu
  //-----------------

  let layoutMenuEl = document.querySelectorAll("#layout-menu");
  layoutMenuEl.forEach(function (element) {
    menu = new Menu(element, {
      orientation: "vertical",
      closeChildren: false,
    });
    // Change parameter to true if you want scroll animation
    window.Helpers.scrollToActive((animate = false));
    window.Helpers.mainMenu = menu;
  });

  // Initialize menu togglers and bind click on each
  let menuToggler = document.querySelectorAll(".layout-menu-toggle");
  menuToggler.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      window.Helpers.toggleCollapsed();
    });
  });

  // Display menu toggle (layout-menu-toggle) on hover with delay
  let delay = function (elem, callback) {
    let timeout = null;
    elem.onmouseenter = function () {
      // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
      if (!Helpers.isSmallScreen()) {
        timeout = setTimeout(callback, 300);
      } else {
        timeout = setTimeout(callback, 0);
      }
    };

    elem.onmouseleave = function () {
      // Clear any timers set to timeout
      document.querySelector(".layout-menu-toggle").classList.remove("d-block");
      clearTimeout(timeout);
    };
  };
  if (document.getElementById("layout-menu")) {
    delay(document.getElementById("layout-menu"), function () {
      // not for small screen
      if (!Helpers.isSmallScreen()) {
        document.querySelector(".layout-menu-toggle").classList.add("d-block");
      }
    });
  }

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName("menu-inner"),
    menuInnerShadow = document.getElementsByClassName("menu-inner-shadow")[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    menuInnerContainer[0].addEventListener("ps-scroll-y", function () {
      if (this.querySelector(".ps__thumb-y").offsetTop) {
        menuInnerShadow.style.display = "block";
      } else {
        menuInnerShadow.style.display = "none";
      }
    });
  }

  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == "show.bs.collapse" || e.type == "show.bs.collapse") {
      e.target.closest(".accordion-item").classList.add("active");
    } else {
      e.target.closest(".accordion-item").classList.remove("active");
    }
  };

  const accordionTriggerList = [].slice.call(
    document.querySelectorAll(".accordion")
  );
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener(
      "show.bs.collapse",
      accordionActiveFunction
    );
    accordionTriggerEl.addEventListener(
      "hide.bs.collapse",
      accordionActiveFunction
    );
  });

  // Auto update layout based on screen size
  window.Helpers.setAutoUpdate(true);

  // Toggle Password Visibility
  window.Helpers.initPasswordToggle();

  // Speech To Text
  window.Helpers.initSpeechToText();

  // Manage menu expanded/collapsed with templateCustomizer & local storage
  //------------------------------------------------------------------

  // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
  if (window.Helpers.isSmallScreen()) {
    return;
  }

  // If current layout is vertical and current window screen is > small

  // Auto update menu collapsed/expanded based on the themeConfig
  window.Helpers.setCollapsed(true, false);
})();

// Simple Bootstrap 5 Dark Mode Toggle
// Versi sederhana yang mudah dipahami

// Fungsi utama untuk mengubah tema
function setTheme(theme) {
  // Set attribute Bootstrap 5
  document.documentElement.setAttribute("data-bs-theme", theme);

  // Simpan pilihan ke localStorage
  localStorage.setItem("theme", theme);

  // Update ikon di navbar
  updateThemeIcon(theme);

  // Update button aktif di dropdown
  updateActiveButton(theme);

  console.log("Theme changed to:", theme);
}

// Update ikon di navbar
function updateThemeIcon(theme) {
  const themeIcon = document.querySelector("#nav-theme .theme-icon-active");

  if (themeIcon) {
    // Hapus semua class ikon
    themeIcon.classList.remove("bx-sun", "bx-moon", "bx-desktop");

    // Tambah ikon sesuai tema
    if (theme === "light") {
      themeIcon.classList.add("bx-sun");
    } else if (theme === "dark") {
      themeIcon.classList.add("bx-moon");
    } else if (theme === "system") {
      themeIcon.classList.add("bx-desktop");
    }
  }
}

// Update button aktif di dropdown
function updateActiveButton(theme) {
  // Hapus class active dari semua button
  document.querySelectorAll("[data-bs-theme-value]").forEach((btn) => {
    btn.classList.remove("active");
    btn.setAttribute("aria-pressed", "false");
  });

  // Tambah class active ke button yang dipilih
  const activeBtn = document.querySelector(`[data-bs-theme-value="${theme}"]`);
  if (activeBtn) {
    activeBtn.classList.add("active");
    activeBtn.setAttribute("aria-pressed", "true");
  }
}

// Deteksi tema sistem
function getSystemTheme() {
  return window.matchMedia("(prefers-color-scheme: dark)").matches
    ? "dark"
    : "light";
}

// Set tema awal saat halaman load
function initTheme() {
  // Ambil tema dari localStorage, atau gunakan system default
  const savedTheme = localStorage.getItem("theme") || "system";

  let actualTheme = savedTheme;

  // Jika pilihan 'system', gunakan preferensi sistem
  if (savedTheme === "system") {
    actualTheme = getSystemTheme();
  }

  // Apply tema
  document.documentElement.setAttribute("data-bs-theme", actualTheme);
  updateThemeIcon(savedTheme);
  updateActiveButton(savedTheme);
}

// Event listener untuk button tema
function bindThemeEvents() {
  document.querySelectorAll("[data-bs-theme-value]").forEach((button) => {
    button.addEventListener("click", function () {
      const theme = this.getAttribute("data-bs-theme-value");

      let actualTheme = theme;

      // Jika pilih system, gunakan preferensi sistem
      if (theme === "system") {
        actualTheme = getSystemTheme();
      }

      // Set tema
      document.documentElement.setAttribute("data-bs-theme", actualTheme);
      localStorage.setItem("theme", theme);

      // Update UI
      updateThemeIcon(theme);
      updateActiveButton(theme);

      console.log(`Theme set to: ${theme} (actual: ${actualTheme})`);
      window.location.reload();
    });
  });
}

// Monitor perubahan sistem tema (untuk mode system)
function watchSystemTheme() {
  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", function () {
      const savedTheme = localStorage.getItem("theme");

      // Hanya update jika user pilih 'system'
      if (savedTheme === "system") {
        const systemTheme = getSystemTheme();
        document.documentElement.setAttribute("data-bs-theme", systemTheme);
        console.log("System theme changed to:", systemTheme);
      }
    });
}

// Fungsi toggle sederhana (light <-> dark)
function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute("data-bs-theme");
  const newTheme = currentTheme === "dark" ? "light" : "dark";
  setTheme(newTheme);
}

// Initialize saat DOM ready
document.addEventListener("DOMContentLoaded", function () {
  // Set tema awal
  initTheme();

  // Bind event listeners
  bindThemeEvents();

  // Monitor sistem tema
  watchSystemTheme();
});

// Export functions ke window untuk akses global
window.setTheme = setTheme;
window.toggleTheme = toggleTheme;
window.initTheme = initTheme;
