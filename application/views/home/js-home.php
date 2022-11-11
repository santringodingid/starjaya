<script>
    $(function() {
        countDownMuammar()
    })

    const countDownMuammar = () => {
        // Mengatur waktu akhir perhitungan mundur
        var countDownDate = new Date("October 23, 2022 07:00:00").getTime();

        // Memperbarui hitungan mundur setiap 1 detik
        var x = setInterval(function() {

            // Untuk mendapatkan tanggal dan waktu hari ini
            var now = new Date().getTime();

            // Temukan jarak antara sekarang dan tanggal hitung mundur
            var distance = countDownDate - now;

            // Perhitungan waktu untuk hari, jam, menit dan detik
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Keluarkan hasil dalam elemen dengan id = "demo"
            document.getElementById("day").innerHTML = days
            document.getElementById("hour").innerHTML = hours
            document.getElementById("minute").innerHTML = minutes
            document.getElementById("second").innerHTML = seconds

            // Jika hitungan mundur selesai, tulis beberapa teks 
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "MUAMMAR SUDAH TERLAKSANA";
            }
        }, 1000);
    }

</script>
</body>

</html>