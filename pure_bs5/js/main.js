/*========================================================
  main.js — nur Features, die im Original existierten:
  - Copyright-Jahr (mit Fallback)
  - Back-to-Top (ersetzt jquery.ui.totop)
  Der Slider laeuft komplett ueber das BS5-Carousel.
=========================================================*/
document.addEventListener('DOMContentLoaded', function () {

    // Copyright-Jahr mit Fallback
    var yearEl = document.getElementById('copyright-year');
    if (yearEl) {
        var y = new Date().getFullYear();
        yearEl.textContent = (y && !isNaN(y)) ? y : '2026';
    }

    // Back-to-Top
    var toTopBtn = document.getElementById('toTop');
    if (toTopBtn) {
        window.addEventListener('scroll', function () {
            toTopBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
        }, { passive: true });

        toTopBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

});
