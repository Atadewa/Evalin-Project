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

// --- PWA: Service Worker registration and offline draft sync ---
(function () {
  // Register service worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('/sw.js')
        .then(function (reg) {
          console.log('ServiceWorker registration successful with scope: ', reg.scope);
        }).catch(function (err) {
          console.warn('ServiceWorker registration failed: ', err);
        });
    });
  }

  // Minimal IndexedDB wrapper for drafts
  const DB_NAME = 'evalin-pwa';
  const STORE_NAME = 'drafts';

  function openDb() {
    return new Promise((resolve, reject) => {
      const req = indexedDB.open(DB_NAME, 1);
      req.onupgradeneeded = function (e) {
        const db = e.target.result;
        if (!db.objectStoreNames.contains(STORE_NAME)) {
          db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
        }
      };
      req.onsuccess = function (e) { resolve(e.target.result); };
      req.onerror = function (e) { reject(e.target.error); };
    });
  }

  async function saveDraft(ujianId, soalId, payload) {
    const db = await openDb();
    return new Promise((resolve, reject) => {
      const tx = db.transaction(STORE_NAME, 'readwrite');
      const store = tx.objectStore(STORE_NAME);
      const item = { ujian_id: ujianId, soal_id: soalId, payload, created_at: new Date().toISOString() };
      const req = store.add(item);
      req.onsuccess = () => resolve(true);
      req.onerror = (e) => reject(e.target.error);
    });
  }

  async function getAllDrafts() {
    const db = await openDb();
    return new Promise((resolve, reject) => {
      const tx = db.transaction(STORE_NAME, 'readonly');
      const store = tx.objectStore(STORE_NAME);
      const req = store.getAll();
      req.onsuccess = () => resolve(req.result);
      req.onerror = (e) => reject(e.target.error);
    });
  }

  async function clearDrafts(ids) {
    const db = await openDb();
    return new Promise((resolve, reject) => {
      const tx = db.transaction(STORE_NAME, 'readwrite');
      const store = tx.objectStore(STORE_NAME);
      let remaining = ids.length;
      if (remaining === 0) return resolve();
      ids.forEach(id => {
        const req = store.delete(id);
        req.onsuccess = () => { remaining--; if (remaining === 0) resolve(); };
        req.onerror = (e) => reject(e.target.error);
      });
    });
  }

  // Hook example: when student answers a question, save draft locally and attempt immediate sync
  // Integrate this with your existing exam page logic: call pwaSaveAnswer when answer changes
  window.pwaSaveAnswer = async function (ujianId, soalId, data) {
    try {
      await saveDraft(ujianId, soalId, data);
      // Try to sync immediately if online
      if (navigator.onLine) {
        await pwaSyncDrafts();
      }
    } catch (e) {
      console.error('Gagal menyimpan draft PWA:', e);
    }
  };

  // Sync drafts to server
  window.pwaSyncDrafts = async function () {
    try {
      const drafts = await getAllDrafts();
      if (!drafts.length) return;

      // Group drafts by ujian_id
      const grouped = drafts.reduce((acc, d) => {
        acc[d.ujian_id] = acc[d.ujian_id] || [];
        acc[d.ujian_id].push({ id: d.id, soal_id: d.soal_id, ...d.payload });
        return acc;
      }, {});

      const savedIds = [];
      for (const ujianId of Object.keys(grouped)) {
        const body = { ujian_id: ujianId, drafts: grouped[ujianId].map(x => ({ soal_id: x.soal_id, jawaban_teks: x.jawaban_teks, opsi_id: x.opsi_id, waktu_dijawab: x.waktu_dijawab })) };

        // Send via fetch to Laravel route - include CSRF token from meta if available
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const res = await fetch('/siswa/ujian/sync-draft', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            ...(token ? { 'X-CSRF-TOKEN': token } : {})
          },
          credentials: 'same-origin',
          body: JSON.stringify(body)
        });

        if (res.ok) {
          // clear those draft ids
          grouped[ujianId].forEach(x => savedIds.push(x.id));
        } else {
          console.warn('Sync draft failed for ujian', ujianId, await res.text());
        }
      }

      if (savedIds.length) await clearDrafts(savedIds);
      return true;
    } catch (e) {
      console.error('Error during PWA draft sync:', e);
      return false;
    }
  };

  // Sync when coming online
  window.addEventListener('online', () => {
    console.log('Koneksi kembali - mencoba sinkronisasi draft...');
    window.pwaSyncDrafts();
  });

})();

