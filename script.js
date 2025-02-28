//Limitasi jadwal dokter
//dr Agus : hari minggu x
//dr maulana : hari sabtu dan minggu x
// document.addEventListener("DOMContentLoaded", function () {
//     const dokterSelect = document.getElementById("dokter");
//     const tglInput = document.getElementById("tgl_kunjungan");

//     function disableDays(date) {
//         let day = date.getDay(); // 0 = Minggu, 6 = Sabtu
//         let dokter = dokterSelect.value;

//         if ((dokter === "dr. Maulana Kurniawan, Sp. KFR" && (day === 0 || day === 6)) ||  // Sabtu & Minggu
//             (dokter === "dr. Agus Prasetyo, Sp. KFR" && day === 0)) {                    // Minggu
//             return true;
//         }
//         return false;
//     }

//     function updateDisabledDates() {
//         let today = new Date();
//         let nextYear = new Date();
//         nextYear.setFullYear(today.getFullYear() + 1); // Batas 1 tahun ke depan

//         let disabledDates = [];
//         for (let d = new Date(today); d <= nextYear; d.setDate(d.getDate() + 1)) {
//             if (disableDays(d)) {
//                 disabledDates.push(d.toISOString().split("T")[0]); // Format YYYY-MM-DD
//             }
//         }

//         tglInput.setAttribute("min", today.toISOString().split("T")[0]); // Set minimal hari ini
//         tglInput.setAttribute("max", nextYear.toISOString().split("T")[0]); // Set maksimal

//         tglInput.addEventListener("input", function () {
//             if (disabledDates.includes(tglInput.value)) {
//                 alert("Tanggal ini tidak tersedia untuk dokter yang dipilih.");
//                 tglInput.value = ""; // Kosongkan input jika tidak valid
//             }
//         });
//     }

//     dokterSelect.addEventListener("change", updateDisabledDates);
// });




//daftar 1 hari sebelum
document.addEventListener("DOMContentLoaded", function () {
    const dokterSelect = document.getElementById("dokter");
    const tglInput = document.getElementById("tgl_kunjungan");

    function disableDays(date) {
        let day = date.getDay(); // 0 = Minggu, 6 = Sabtu
        let dokter = dokterSelect.value;

        if ((dokter === "dr. Maulana Kurniawan, Sp. KFR" && (day === 0 || day === 6)) ||  // Sabtu & Minggu
            (dokter === "dr. Agus Prasetyo, Sp. KFR" && day === 0)) {                    // Minggu
            return true;
        }
        return false;
    }
    
    function validateForm() {
        var nikInput = document.querySelector('input[name="nik"]');
        if (nikInput.value.length !== 13) {
            alert("No Kartu BPJS harus terdiri dari 13 angka.");
            return false;
        }
        return true;
    }

    // Membatasi input No Kartu BPJS hanya sampai 13 angka
    var nikInput = document.querySelector('input[name="nik"]');
    nikInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, ''); // Hanya angka yang diperbolehkan
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }
    });

    function updateDisabledDates() {
        let today = new Date();
        today.setDate(today.getDate() + 1); // Minimal 1 hari dari sekarang

        let nextYear = new Date();
        nextYear.setFullYear(today.getFullYear() + 1); // Batas 1 tahun ke depan

        let disabledDates = [];
        for (let d = new Date(today); d <= nextYear; d.setDate(d.getDate() + 1)) {
            if (disableDays(d)) {
                disabledDates.push(d.toISOString().split("T")[0]); // Format YYYY-MM-DD
            }
        }

        tglInput.setAttribute("min", today.toISOString().split('T')[0]); // Set minimal 1 hari ke depan
        tglInput.setAttribute("max", nextYear.toISOString().split('T')[0]); // Set maksimal

        tglInput.addEventListener("input", function () {
            if (disabledDates.includes(tglInput.value)) {
                alert("Tanggal ini tidak tersedia untuk dokter yang dipilih.");
                tglInput.value = ""; // Kosongkan input jika tidak valid
            }
        });
    }
    dokterSelect.addEventListener("change", updateDisabledDates);
    updateDisabledDates(); // Jalankan saat halaman dimuat

    

});

