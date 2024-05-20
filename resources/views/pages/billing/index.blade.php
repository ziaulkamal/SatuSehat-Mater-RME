@extends('layout')

@section('page')
<div class="main-content-inner">
    <div class="main-content-wrap" id="table">
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
        <!-- order-list -->
        <div class="wg-box">
            <div class="flex flex-wrap">
                <div class="wg-filter flex-grow">

                </div>
                <a class="tf-button style-1 w208" href="#" id="add-new-button"><i class="icon-file-text"></i>Buat Bill Baru</a>
            </div>
            <div class="wg-table table-all-category">
                <ul class="table-title flex gap10 mb-5">
                    <li>
                        <div class="body-title">Nama Fasyankes</div>
                    </li>
                    <li>
                        <div class="body-title">Biaya Awal</div>
                    </li>
                    <li>
                        <div class="body-title">Biaya Langganan</div>
                    </li>
                    <li>
                        <div class="body-title">Jatuh Tempo</div>
                    </li>
                    <li>
                        <div class="body-title">Total </div>
                    </li>
                    <li>
                        <div class="body-title">Status </div>
                    </li>

                    <li>
                        <div class="body-title">Action</div>
                    </li>
                </ul>
                <ul id="bill-list" class="flex flex-column">
                    <!-- Data will be loaded here -->
                </ul>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10">
                <div class="text-tiny">Showing <span id="showing-entries">0</span> entries</div>
                <ul id="pagination" class="wg-pagination">
                    <!-- Pagination links will be added here -->
                </ul>
            </div>
        </div>
        <!-- /order-list -->
    </div>

    <div class="main-content-wrap" id="form" style="display: none">
        <form id="form-add-new-bill" class="form-add-new-bill form-style-2" method="POST">
            <div class="wg-box">
                <div class="left">
                    <h5 class="mb-4">Tambah Baru</h5>
                    <div class="body-text">Tambah baru {{ $title }}</div>
                </div>
                <div class="right flex-grow">
                    <fieldset class="name mb-24">
                        <div class="body-title mb-10">Pilih Fasyankes</div>
                        <div class="select">
                            <select name="const_users" id="const_users">

                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="name mb-24">
                        <div class="body-title mb-10">Jenis Fasyankes</div>
                        <div class="select">
                            <select name="jenis_fasyankes" id="jenis_fasyankes">
                                <option value="" disabled selected>--Pilih--</option>
                                <option value="1">Klinik</option>
                                <option value="2">Puskesmas</option>
                                <option value="3">Rumah Sakit</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="email mb-24">
                        <div class="body-title mb-10">Tanggal Mulai Transaksi</div>
                        <input id="tanggal-mulai" class="flex-grow" type="text" name="tanggal_mulai" tabindex="0" value="" aria-required="true" required>
                    </fieldset>
                    <fieldset class="email mb-24">
                        <div class="body-title mb-10">Biaya Awal</div>
                        <input id="harga_awal" class="flex-grow rupiah" type="text" name="harga_awal" tabindex="0" value="" aria-required="true" required>
                    </fieldset>
                    <fieldset class="email mb-24">
                        <div class="body-title mb-10">Biaya Langganan</div>
                        <input id="harga_langganan" class="flex-grow rupiah" type="text" name="harga_langganan" tabindex="0" value="" aria-required="true" required>
                    </fieldset>

                </div>
            </div>
            <div class="bot">
                <button class="tf-button style-1" id="back-button" type="button">Kembali</button>
                <button class="tf-button w180" type="submit">Simpan</button>
            </div>
        </form>
    </div>

</div>
@endsection

@section('footerScript')
<!-- Tambahkan JavaScript moment.js dan flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>

    function bayar(constUsers) {

        const formattedDate = new Date().toISOString().slice(0,10);
        let paymentRoutes = `{{ route('due.payment', ['conditional' => ':update','const_users' => ':id']) }}`;
        paymentRoutes = paymentRoutes.replace(':id', constUsers);
        paymentRoutes = paymentRoutes.replace(':update', formattedDate);
        $.ajax({
            url: paymentRoutes,
            method: 'GET',
            success: function (response) {
                alert('Berhasil melakukan perpanjangan')

                fetchBilling(); // Optionally, refresh the list
            },
            error: function (xhr, status, error) {
                console.error('error: ', error);
            }
        });
    }

</script>
<script>
    document.getElementById('form-add-new-bill').addEventListener('submit', async function(event) {
        event.preventDefault();

        // Get the CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Collect form data
        const formData = new FormData(this);
        const data = {
            const_users: formData.get('const_users'),
            tanggal_mulai: formData.get('tanggal_mulai'),
            harga_awal: formData.get('harga_awal'),
            harga_langganan: formData.get('harga_langganan'),
            jenis_fasyankes: formData.get('jenis_fasyankes'),

        };

        try {
            const response = await fetch("{{ route('bill.payment') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            console.log(response);
            if (response.ok) {
                alert('Data berhasil disimpan');
                populateConstUsersSelect();
                this.reset();
            } else {

                alert('Gagal');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data');
        }
    });
</script>

<script>
    function populateConstUsersSelect() {
        const routesGB = `{{ route('gb.fasyankes') }}`;
        // Menggunakan jQuery AJAX untuk mengambil data dari API
        $.ajax({
            url: routesGB,
            method: 'GET',
            success: function (data) {
                // Bersihkan opsi yang ada
                $('#const_users').empty();

                $('#const_users').append('<option value="" disabled selected>--Pilih--</option>');

                // Tambahkan opsi baru berdasarkan data dari API
                data.forEach(function (item) {
                    $('#const_users').append(
                        $('<option>', {
                            value: item.const_users,
                            text: item.nama_fasyankes
                        })
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#const_users').html('<option value="">Error loading data</option>');
            }
        });
    }

    // Panggil fungsi tersebut saat dokumen siap
    $(document).ready(function () {
        populateConstUsersSelect();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const elements = document.querySelectorAll('.rupiah');

        elements.forEach(function(element) {
            element.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^,\d]/g, '');
                if (value) {
                    value = formatRupiah(value, 'Rp. ');
                }
                e.target.value = value;
            });
        });

        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString();
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi flatpickr pada input date
        flatpickr("#tanggal-mulai", {
            dateFormat: "d-m-Y",
            locale: "id",
            onChange: function(selectedDates, dateStr, instance) {
                // Menggunakan moment.js untuk memformat tanggal yang dipilih
                let formattedDate = moment(selectedDates[0]).format('YYYY-MM-DD');
                console.log(formattedDate); // Menampilkan tanggal yang diformat di console
            }
        });
    });
</script>
<script>
    // action button
    document.addEventListener('DOMContentLoaded', function() {
        // Get the button and content elements
        var addButton = document.getElementById('add-new-button');
        var backButton = document.getElementById('back-button');
        var tableContent = document.getElementById('table');
        var formContent = document.getElementById('form');

        // Add click event listener to add button
        addButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            // Hide the table content
            tableContent.style.display = 'none';

            // Show the form content
            formContent.style.display = 'block';
        });

        // Add click event listener to back button
        backButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            tableContent.style.display = 'block';

            // Show the form content
            formContent.style.display = 'none';
            fetchBilling();
        });
    });
</script>

<script>
    function fetchBilling(page = 1) {
        $.ajax({
            url: "{{ route('all.payment') }}",
            method: 'GET',
            data: {
                page: page
            },
            success: function (data) {
                var list = $('#bill-list');
                list.empty(); // Clear previous data
                data.data.forEach(function (item) {
                    var row = '<li class="product-item gap14">' +
                        '<div class="flex items-center gap20 flex-grow">' +
                        '<div class="name"><a href="{{ url("#") }}" class="body-title-2">' + (item.client.nama_fasyankes) + '</a></div>' +
                        '<div class="body-text">' +
                        '<span class="body-title">' + formatRupiah(item.harga_awal) + '</span>' +
                        '</div>' +
                        '<div class="body-text">' +
                        '<span class="body-title">' + formatRupiah(item.harga_langganan) + '</span>' +
                        '</div>' +
                        '<div class="body-text">' +
                        '<span class="short-text">' + formatDate(item.jatuh_tempo) + '</span>' +
                        '</div>' +
                        '<div class="body-text">' +
                        '<span class="short-text">' + (item.total_bayar == 0 ? 'Belum Ada' : item.total_bayar + ' Kali') + '</span>' +
                        '</div>' +
                        '<div><div class="block-' + (item.status == 'active' ? '' : 'not-') + 'available">' + (
                            item.status ? titleCase(item.status) : titleCase(item.status)) +
                        '</div></div>' +
                        '<div class="list-icon-function">' +
                        '<div class="item eye" data-const-users="' + item
                            .const_users + '"><i class="icon-airplay"></i></div>' +
                        '<div class="item edit" data-const-users="' + item
                            .const_users + '"><i class="icon-layers"></i></div>' +
                        '</div>' +
                        '</li>';
                    list.append(row);
                });

                // Update showing entries text
                $('#showing-entries').text(data.to);

                // Update pagination
                var pagination = $('#pagination');
                pagination.empty();
                if (data.prev_page_url) {
                    pagination.append('<li><a href="#" data-page="' + (data.current_page - 1) +
                        '"><i class="icon-chevron-left"></i></a></li>');
                }
                for (let i = 1; i <= data.last_page; i++) {
                    pagination.append('<li' + (i === data.current_page ? ' class="active"' : '') +
                        '><a href="#" data-page="' + i + '">' + i + '</a></li>');
                }
                if (data.next_page_url) {
                    pagination.append('<li><a href="#" data-page="' + (data.current_page + 1) +
                        '"><i class="icon-chevron-right"></i></a></li>');
                }

                // Add click event for pagination links
                $('#pagination a').on('click', function (event) {
                    event.preventDefault();
                    var page = $(this).data('page');
                    fetchFasyankes(page);
                });

                // Add click event for verify buttons
                $('.item.eye').on('click', function () {
                    var constUsers = $(this).data('const-users');
                    var konfirmasi = confirm("Apakah Anda yakin ingin melakukan pembayaran untuk pengguna ini?");

                    // Jika pengguna menekan tombol OK (yakin)
                    if (konfirmasi) {
                        bayar(constUsers);
                    }
                });

                // Add click event for verify buttons
                $('.item.edit').on('click', function () {
                    var constUsers = $(this).data('const-users');
                    let routesHistoy = `{{ route('payment.history', ['const_users' => ':id']) }}`;
                    routesHistoy = routesHistoy.replace(':id', constUsers);
                    window.location.href = routesHistoy;
                });


            }
        });
    }

    fetchBilling();

    function titleCase(str) {
        return str.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
    }

    function formatRupiah(angka) {
        var reverse = angka.toString().split('').reverse().join('');
        var ribuan = reverse.match(/\d{1,3}/g);
        var hasil = ribuan.join('.').split('').reverse().join('');
        return 'Rp ' + hasil;
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
@endsection

@section('headerScript')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>

</style>
@endsection
