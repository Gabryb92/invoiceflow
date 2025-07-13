import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

// Dice ad Alpine di avviarsi solo dopo che Livewire ha caricato o navigato una pagina.
document.addEventListener("livewire:navigated", () => {
    Alpine.start();
});
