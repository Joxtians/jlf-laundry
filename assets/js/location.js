function getLocation() {
    const statusText = document.getElementById("status_lokasi");
    const inputLink = document.getElementById("link_maps");
    const inputAlamat = document.getElementById("alamat_jemput");

 
    inputLink.value = "";
    statusText.innerHTML = "<span class='text-primary'><span class='spinner-border spinner-border-sm me-2'></span>Mencari titik satelit...</span>";

    if (navigator.geolocation) {
        
        const options = {
            enableHighAccuracy: true, 
            timeout: 15000,           
            maximumAge: 0             
        };

        navigator.geolocation.getCurrentPosition(showPosition, showError, options);
    } else {
        statusText.innerHTML = "<span class='text-danger'>Browser tidak mendukung GPS.</span>";
    }
}

function showPosition(position) {
    const lat = position.coords.latitude;
    const long = position.coords.longitude;

   
    const gmapLink = `https://www.google.com/maps?q=${lat},${long}`;
    
   
    const inputLink = document.getElementById("link_maps");
    inputLink.value = gmapLink;
   

    
    const statusText = document.getElementById("status_lokasi");
    statusText.innerHTML = "<span class='text-success fw-bold'><i class='bi bi-check-circle-fill me-1'></i>Lokasi Akurat Ditemukan!</span>";

    
    const inputAlamat = document.getElementById("alamat_jemput");
    if(inputAlamat) {
        if(inputAlamat.value.trim() === "") {
            inputAlamat.placeholder = "Sedang melacak nama jalan otomatis...";
        }

      
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if(data.display_name) {
                    inputAlamat.value = data.display_name;
                }
            })
            .catch(err => {
                console.log("Gagal ambil nama jalan, user harus ketik manual.");
                inputAlamat.placeholder = "Nama jalan tidak terdeteksi, silakan ketik manual.";
            });
    }
}

function showError(error) {
    const statusText = document.getElementById("status_lokasi");
    const inputLink = document.getElementById("link_maps");
    
    
    inputLink.readOnly = false;
    
    let pesan = "";
    switch(error.code) {
        case error.PERMISSION_DENIED:
            pesan = "Izin lokasi ditolak. Mohon izinkan akses lokasi di browser.";
            break;
        case error.POSITION_UNAVAILABLE:
            pesan = "Sinyal GPS tidak ditemukan. Pastikan GPS HP aktif.";
            break;
        case error.TIMEOUT:
            pesan = "Waktu habis mencari sinyal. Coba di luar ruangan.";
            break;
        default:
            pesan = "Terjadi kesalahan yang tidak diketahui.";
    }
    
    statusText.innerHTML = `<span class='text-danger'><i class='bi bi-exclamation-circle me-1'></i>${pesan}</span>`;
    inputLink.placeholder = "Gagal deteksi. Silakan paste link Maps manual.";
}