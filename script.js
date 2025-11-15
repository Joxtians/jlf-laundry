document.addEventListener('DOMContentLoaded', () => {
    // Logika Splash Screen SUDAH DIHAPUS.

    // 1. Logika Pop-up Customer Service (CS)
    const contactBtn = document.getElementById('contact-btn');
    const csPopup = document.getElementById('popup-cs');

    if (contactBtn && csPopup) {
        contactBtn.onclick = (e) => {
            e.preventDefault();
            let currentCsPopup = document.getElementById('popup-cs');
            if (currentCsPopup) {
                currentCsPopup.querySelector('.modal-content').innerHTML = `
                    <span class="close-btn">&times;</span>
                    <h4>Hubungi Customer Service</h4>
                    <p>Kami siap melayani Anda 24/7. Pilih salah satu cara:</p>
                    <ul>
                        <li><a href="tel:+62xxxxxxx" style="color: var(--color-primary);">Telepon Langsung</a></li>
                        <li><a href="https://wa.me/62xxxxxxx" target="_blank" style="color: var(--color-primary);">WhatsApp Chat</a></li>
                    </ul>
                `;
                currentCsPopup.style.display = 'block';
            }
        };
    }

    document.body.addEventListener('click', (event) => {
        if (event.target.classList.contains('close-btn')) {
            event.target.closest('.modal').style.display = 'none';
        }
    });

    window.onclick = (event) => {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };


    // 2. Logika Formulir Login (untuk index.html)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // ... (validasi username/password) ...

            alert('Login Berhasil! Selamat Datang di JLF & Co. Laundry.');
            // DIUBAH: Arahkan ke beranda.html
            window.location.href = 'beranda.html'; 
        });
    }

    // 3. Logika Formulir Register (untuk register.html)
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // ... (validasi form registrasi) ...

            alert('Registrasi Berhasil! Silakan Masuk.');
            // DIUBAH: Arahkan kembali ke index.html (halaman login) setelah daftar
            window.location.href = 'index.html';
        });
    }
});