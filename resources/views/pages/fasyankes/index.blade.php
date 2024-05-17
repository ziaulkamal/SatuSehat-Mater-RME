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
                <a class="tf-button style-1 w208" href="#" id="add-new-button"><i class="icon-file-text"></i>Tambah Baru</a>
            </div>
            <div class="wg-table table-all-category">
                <ul class="table-title flex gap10 mb-5">
                    <li>
                        <div class="body-title">Nama Fasyankes</div>
                    </li>
                    <li>
                        <div class="body-title">Client Key</div>
                    </li>
                    <li>
                        <div class="body-title">Organization ID</div>
                    </li>
                    <li>
                        <div class="body-title">API Key</div>
                    </li>
                    <li>
                        <div class="body-title">Status</div>
                    </li>
                    <li>
                        <div class="body-title">Action</div>
                    </li>
                </ul>
                <ul id="fasyankes-list" class="flex flex-column">
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
        <form id="form-add-new-user" class="form-add-new-user form-style-2" method="POST">
            <div class="wg-box">
                <div class="left">
                    <h5 class="mb-4">Tambah Baru</h5>
                    <div class="body-text">Tambah data baru {{ $title }}</div>
                </div>
                <div class="right flex-grow">
                    <fieldset class="name mb-24">
                        <div class="body-title mb-10">Client Key</div>
                        <input class="flex-grow" type="text" name="client_id" tabindex="0" value="" aria-required="true" required>
                    </fieldset>
                    <fieldset class="email mb-24">
                        <div class="body-title mb-10">Secret Key</div>
                        <input class="flex-grow" type="text" name="secret_id" tabindex="0" value="" aria-required="true" required>
                    </fieldset>
                    <fieldset class="email mb-24">
                        <div class="body-title mb-10">Organization ID</div>
                        <input class="flex-grow" type="text" name="organisasi_id" tabindex="0" value="" aria-required="true" required>
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

<script>
    document.getElementById('form-add-new-user').addEventListener('submit', async function(event) {
        event.preventDefault();

        // Get the CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Collect form data
        const formData = new FormData(this);
        const data = {
            client_id: formData.get('client_id'),
            secret_id: formData.get('secret_id'),
            organisasi_id: formData.get('organisasi_id')
        };

        try {
            const response = await fetch("{{ route('store.fasyankes') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok) {
                alert('Data berhasil disimpan');
                this.reset();
            } else {
                console.log(result)
                alert('Client Id / Secret Id / Organization ID sudah ada di database');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data');
        }
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
        fetchFasyankes(); // Optionally, refresh the list
        // Hide the table content
        tableContent.style.display = 'block';

        // Show the form content
        formContent.style.display = 'none';
    });
});


</script>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert(`Text disalin : ${text}`);
        }).catch(err => {

            console.error("Could not copy text: ", err);
        });
    }

    function fetchFasyankes(page = 1) {
        $.ajax({
            url: "{{ route('all.fasyankes') }}",
            method: 'GET',
            data: {
                page: page
            },
            success: function (data) {
                var list = $('#fasyankes-list');
                list.empty(); // Clear previous data
                data.data.forEach(function (item) {
                    var row = '<li class="product-item gap14">' +
                        '<div class="flex items-center justify-between gap20 flex-grow">' +
                        '<div class="name"><a href="{{ url("#") }}" class="body-title-2">' + (item
                            .nama_fasyankes ? item.nama_fasyankes : '-') + '</a></div>' +
                        '<div class="body-text">' +
                        '<span class="short-text">' + item.client_id + '</span>' +
                        '<span class="copy-button" onclick="copyToClipboard(\'' + item.client_id +
                        '\')"><i class="icon-copy"></i></span>' +
                        '</div>' +

                        '<div class="body-text">' +
                        '<span class="short-text">' + item.organisasi_id + '</span>' +
                        '<span class="copy-button" onclick="copyToClipboard(\'' + item.organisasi_id +
                        '\')"><i class="icon-copy"></i></span>' +
                        '</div>' +
                        '<div class="body-text">' +
                        '<span class="short-text">' + item.const_users + '</span>' +
                        '<span class="copy-button" onclick="copyToClipboard(\'' + item.const_users +
                        '\')"><i class="icon-copy"></i></span>' +
                        '</div>' +
                        '<div><div class="block-' + (item.status ? '' : 'not-') + 'available">' + (
                            item.status ? titleCase(item.status) : 'Perlu Validasi') +
                        '</div></div>' +
                        '<div class="list-icon-function">' +
                        (item.status ? '' : '<div class="item eye" data-const-users="' + item
                            .const_users + '"><i class="icon-refresh-ccw"></i></div>') +
                        '<div class="item trash" data-const-users="' + item.const_users +
                        '"><i class="icon-trash-2"></i></div>' +
                        '</div>' +
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
                    verifyFasyankes(constUsers);
                });

                // Add click event for delete buttons
                $('.item.trash').on('click', function () {
                    var constUsers = $(this).data('const-users');
                    const confirmation = confirm('Apakah yakin ingin menghapus ini ?');
                    if (confirmation) {

                        deleteFasyankes(constUsers);
                    }
                });
            }
        });
    }

    function verifyFasyankes(constUsers) {
        let refreshRoute = `{{ route('get.fasyankes', ['const_users' => ':id']) }}`;
        refreshRoute = refreshRoute.replace(':id', constUsers);
        $.ajax({
            url: refreshRoute,
            method: 'GET',
            success: function (response) {
                if (response['data'][0] === null) {
                    alert(`Client_id dan Secret_id tidak valid, data ini akan terhapus secara otomatis !`);
                } else {
                    alert(
                        `Verifikasi berhasil ! Fasyankes: ${response['data'][0]} dengan status ${response['data'][2]}`)
                }
                console.log(response);
                fetchFasyankes(); // Optionally, refresh the list
            },
            error: function (xhr, status, error) {
                console.error('Failed to refresh fasyankes: ', error);
            }
        });
    }

    function deleteFasyankes(constUsers) {
        let refreshRoute = `{{ route('del.fasyankes', ['const_users' => ':id']) }}`;
        refreshRoute = refreshRoute.replace(':id', constUsers);
        $.ajax({
            url: refreshRoute,
            method: 'GET',
            success: function (response) {
                if (response['data'][0] === null) {
                    alert(`Client_id dan Secret_id tidak valid, data ini akan terhapus secara otomatis !`);
                } else {
                    alert(
                        `Data di Hapus! Fasyankes: ${response['data'][0]} dengan status ${response['data'][2]}`);
                }

                fetchFasyankes(); // Optionally, refresh the list
            },
            error: function (xhr, status, error) {
                console.error('Failed to refresh fasyankes: ', error);
            }
        });
    }

    // Initial fetch
    fetchFasyankes();

    function titleCase(str) {
        return str.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
    }

</script>
@endsection

@section('headerScript')

<style>
    .short-text {
        display: inline-block;
        max-width: 100px;
        /* Adjust as needed */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: middle;
    }

    .copy-button {
        cursor: pointer;
        margin-left: 5px;
    }

</style>
@endsection
