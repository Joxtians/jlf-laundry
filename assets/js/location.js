function getLocation() {
    const statusText = document.getElementById("status_lokasi");
    const inputLink = document.getElementById("link_maps");
    const inputAlamat = document.getElementById("alamat_jemput");

    if (navigator.geolocation) {
        statusText.innerHTML = "<span class='text-primary'><span class='spinner-border spinner-border-sm me-1'></span>Sedang mencari titik koordinat...</span>";
        
        navigator.geolocation.getCurrentPosition(showPosition, showError, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        });
    } else {
        statusText.innerHTML = "<span class='text-danger'>Browser Anda tidak mendukung Geolocation.</span>";
    }
}

function showPosition(position) {
    const lat = position.coords.latitude;
    const long = position.coords.longitude;
    const gmapLink = `https://www.google.com/maps?q=LATITUDE,LONGITUDE${lat},${long}`;
    
    document.getElementById("link_maps").value = gmapLink;
    document.getElementById("status_lokasi").innerHTML = "<span class='text-success fw-bold'><i class='bi bi-check-circle-fill me-1'></i>Lokasi berhasil ditemukan!</span>";


    const inputAlamat = document.getElementById("alamat_jemput");
    if(inputAlamat) {
        inputAlamat.placeholder = "Sedang mengambil nama jalan...";
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}`)
            .then(response => response.json())
            .then(data => {
                if(data.display_name) {
                    inputAlamat.value = data.display_name;
                }
            });
    }
}

function showError(error) {
    const statusText = document.getElementById("status_lokasi");
    const inputLink = document.getElementById("link_maps");
    
    switch(error.code) {
        case error.PERMISSION_DENIED:
            statusText.innerHTML = "<span class='text-danger'>Izin lokasi ditolak.</span>";
            inputLink.readOnly = false;
            inputLink.placeholder = "Izin ditolak. Silakan paste link manual.";
            break;
        default:
            statusText.innerHTML = "<span class='text-danger'>Gagal mengambil lokasi. Coba lagi.</span>";
    }
}