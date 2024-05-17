@extends('layout')

@section('page')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>{{ $title ?? env('APP_NAME') }}</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="#">
                        <div class="text-tiny">{{ $title ?? env('APP_NAME') }}</div>
                    </a>
                </li>

            </ul>
        </div>
        <!-- order-track -->
        <div class="wg-box mb-20">
            <div class="order-track">
                <div class="content">
                    <h5 class="mb-20" id="title-object">---------------------------</h5>
                    <div class="infor mb-10">
                        <div class="body-text">Status</div>
                        <div class="body-title-2" id="status"></div>
                    </div>
                    <div class="infor mb-10">
                        <div class="body-text">ID Organisasi</div>
                        <div class="body-title-2" id="organisasi"></div>
                    </div>
                    <div class="infor mb-10">
                        <div class="body-text">Expired</div>
                        <div class="body-title-2" id="expired"></div>
                    </div>
                    <div class="infor mb-10">
                        <div class="body-text">Kelas</div>
                        <div class="body-title-2" id="kelas"></div>
                    </div>
                    <div class="infor mb-20">
                        <div class="body-text">Total</div>
                        <div class="body-title-2" id="total"></div>
                    </div>
                    <div class="flex gap10 flex-wrap">
                        <div class="tf-button style-1 w230" data-const-users="" id="suspend">Suspend</div>
                        <div class="tf-button w230" data-const-users="" id="activate">Aktifkan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wg-box">
            <div class="wg-table table-order-track">
                <ul class="table-title flex mb-24 gap20">
                    <li>
                        <div class="body-title">Tanggal Transaksi</div>
                    </li>
                    <li>
                        <div class="body-title">Nilai Tagihan</div>
                    </li>
                    <li>
                        <div class="body-title">Status</div>
                    </li>
                    <li>
                        <div class="body-title">Serial Transaksi</div>
                    </li>
                </ul>
                <ul class="flex flex-column gap14" id="history">


                </ul>
            </div>
        </div>
        <!-- /table -->
    </div>

</div>
@endsection

@section('footerScript')
<script>
    function loadData() {
        const constUsers = window.location.pathname.split('/').filter(Boolean).pop();
        let routes = `{{ route('detail.payment', ['const_users' => ':id']) }}`;
        routes  = routes.replace(':id', constUsers);
        // Menggunakan jQuery AJAX untuk mengambil data dari API
        $.ajax({
            url: routes,
            method: 'GET',
            success: function (response) {
                $('#organisasi').text(response.profile.client.organisasi_id);
                $('#suspend').attr('data-const-users', response.profile.const_users);
                $('#activate').attr('data-const-users', response.profile.const_users);
                $('#expired').text(formatDate(response.profile.jatuh_tempo));
                switch(response.profile.jenis_fasyankes) {
                    case 1:
                        $('#kelas').text('Klinik');
                        break;
                    case 2:
                        $('#kelas').text('Puskesmas');
                        break;
                    case 3:
                        $('#kelas').text('Rumah Sakit');
                        break;
                }
                $('#total').text(`Telah Transaksi Sebanyak ${response.profile.total_bayar} Kali`);
                switch (response.profile.status) {
                    case 'suspend':
                        $('#status').html(`<div class="body-title block-not-available">Di Tangguhkan</div>`);
                        break;
                    default:
                        $('#status').html(`<div class="body-title block-available">Active</div>`);
                        break;
                }

                const tables = $('#history');
                tables.empty(); // Kosongkan elemen #history
                $('#title-object').text(response.fasyankes); // Set teks elemen #title-object

                let list = ''; // Inisialisasi variabel untuk menyimpan elemen list

                response.data.forEach(function(item){
                    list += `
                    <li class="cart-totals-item">
                        <div class="body-text">${item.created_at}</div>
                        <div class="body-text">${formatRupiah(item.total_bayar)}</div>
                        <div class="body-text">${item.status === 0 ? '<div class="block-published">Keluarkan Invoice</div>' : '<div class="block-available">Selesai</div>'}</div>
                        <div class="body-text">${item.transaction_id}</div>
                    </li>
                    <li class="divider"></li>
                    `;
                });

                // Tambahkan elemen list ke dalam elemen #history
                tables.append(list);
            },
            error: function (error) {
                console.error('Error:', error); // Debugging: Cetak kesalahan ke konsol
            }
        });

    }
    $(document).ready(function() {
        loadData();
        // Add click event for verify buttons
        $('#activate').on('click', function () {
            var constUsers = $(this).data('const-users');
            var konfirmasi = confirm("Apakah Anda yakin ingin melakukan aktivasi untuk pengguna ini?");

            // Jika pengguna menekan tombol OK (yakin)
            if (konfirmasi) {
                updateStatus(constUsers,'active');
            }
        });
        // Add click event for verify buttons
        $('#suspend').on('click', function () {
            var constUsers = $(this).data('const-users');
            var konfirmasi = confirm("Apakah Anda yakin ingin melakukan pengangguhan untuk pengguna ini?");

            // Jika pengguna menekan tombol OK (yakin)
            if (konfirmasi) {
                updateStatus(constUsers,'suspend');
            }
        });
    })

    function formatRupiah(angka) {
        var reverse = angka.toString().split('').reverse().join('');
        var ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return 'Rp ' + ribuan;
    }

    function formatDate(inputDate) {
        // Mendefinisikan daftar nama hari
        var dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

        // Mendefinisikan daftar nama bulan
        var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // Mengubah string input menjadi objek Date
        var date = new Date(inputDate);

        // Mendapatkan nama hari, tanggal, bulan, dan tahun dari objek Date
        var dayName = dayNames[date.getDay()];
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();

        // Menggabungkan hasilnya dalam format yang diinginkan
        var formattedDate = dayName + ', ' + day + ' ' + monthNames[monthIndex] + ' ' + year;

        return formattedDate;
    }
</script>
<script>
    function updateStatus(constUsers,status){
        let routesStatus = `{{ route('set.license', ['const_users' => ':id', 'status' => ':status']) }}`;
        routesStatus = routesStatus.replace(':id', constUsers);
        routesStatus = routesStatus.replace(':status', status);
        $.ajax({
            url: routesStatus,
            method: 'GET',
            success: function (response) {
                alert(response.message);
                loadData();
            },
            error: function (error) {
                alert('Tindakan Gagal !');
                console.error('Error:', error); // Debugging: Cetak kesalahan ke konsol
            }
        });
    }
</script>
@endsection
