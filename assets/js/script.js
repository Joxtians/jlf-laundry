// assets/js/script.js YANG BARU

document.addEventListener('DOMContentLoaded', function() {
    // Hanya logic untuk tombol hamburger menu (Mobile)
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            // Bootstrap sudah menangani ini, tapi kalau butuh custom logic taruh disini
            console.log('Menu diklik');
        });
    }
});