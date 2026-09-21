// Import Alpine & Plugin Collapse
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

// Daftarkan plugin
Alpine.plugin(collapse);

// Masukkan ke object window
window.Alpine = Alpine;

// Jalankan Alpine
Alpine.start();
