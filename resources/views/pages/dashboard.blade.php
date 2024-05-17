@extends('layout')
{{-- @dd(session('user_email')) --}}
@section('page')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="tf-section-4 mb-30">
            @include('components.card')
        </div>
        <div class="tf-section-2 mb-30">
            <!-- product-overview -->
            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Top 10 Transaksi Terakhir</h5>
                    <div class="dropdown default">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="icon-more"><i class="icon-more-horizontal"></i></span>
                        </button>

                    </div>
                </div>
                <div class="wg-table table-top-selling-product">
                    <ul class="table-title flex gap20 mb-14">
                        <li>
                            <div class="body-title">Nama Fasyankes</div>
                        </li>
                        <li>
                            <div class="body-title">Serial Transaksi</div>
                        </li>
                        <li>
                            <div class="body-title">Nominal</div>
                        </li>
                        <li>
                            <div class="body-title">Tanggal</div>
                        </li>
                    </ul>
                    <div class="divider mb-14"></div>
                    <ul class="flex flex-column gap10">
                        @foreach ($table['transac_user'] as $item)

                        <li class="product-item gap14">
                            <div class="flex items-center justify-between flex-grow">
                                <div class="name">
                                    <div class="body-title-2">{{ $item->client->nama_fasyankes }}</div>
                                </div>
                                <div class="body-text">
                                   <div class="short-text copy-button" onclick="copyToClipboard('{{ $item->transaction_id }}')">{{ $item->transaction_id }}</div><i class="icon-copy" onclick="copyToClipboard('{{ $item->transaction_id }}')"></i>
                                </div>
                                <div class="body-text">{{ 'IDR ' . number_format($item->total_bayar, 0, ',', '.') }}</div>
                                <div class="body-text">{{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</div>

                            </div>
                        </li>
                        <li class="divider"></li>
                        @endforeach

                    </ul>
                </div>
            </div>
            <!-- /product-overview -->
            <!-- orders -->
            <div class="wg-box">
                <div class="flex items-center justify-between">
                    <h5>Top 5 Tangguhan</h5>
                    <div class="dropdown default">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <span class="icon-more"><i class="icon-more-horizontal"></i></span>
                        </button>

                    </div>
                </div>
                <div class="wg-table table-orders-1">
                    <ul class="table-title flex gap10 mb-14">
                        <li>
                            <div class="body-title">Nama Fasyankes</div>
                        </li>
                        <li>
                            <div class="body-title">Tanggal Mulai</div>
                        </li>
                        <li>
                            <div class="body-title">Tanggal Expired</div>
                        </li>
                    </ul>
                    <div class="divider mb-14"></div>
                    <ul class="flex flex-column gap18">
                        @if (count($table['suspend_user']) == 0)
                        <div class="flex items-center justify-between flex-grow gap10">
                             <div class="name">
                                 <li class="body-text">Tidak ada data</li>
                             </div>
                        </div>
                        @endif
                        @foreach ($table['suspend_user'] as $item)
                            <li class="product-item gap14">
                                <div class="flex items-center justify-between flex-grow gap10">
                                    <div class="name">
                                        <div class="body-text">{{ $item->client->nama_fasyankes }}</div>
                                    </div>
                                    <div class="body-text">{{ $item->tanggal_mulai }}</div>
                                    <div class="body-text">{{ Carbon\Carbon::parse($item->jatuh_tempo)->translatedFormat('d F Y') }}</div>

                                </div>
                            </li>
                            <li class="divider"></li>

                        @endforeach

                    </ul>
                </div>
            </div>
            <!-- /orders -->
        </div>

    </div>
</div>
@endsection

@section('footerScript')
<script src="{{ asset('build/js/apexcharts/apexcharts.js') }}"></script>
<script src="{{ asset('build/js/apexcharts/line-chart-1.js') }}"></script>
<script src="{{ asset('build/js/apexcharts/line-chart-2.js') }}"></script>
<script src="{{ asset('build/js/apexcharts/line-chart-3.js') }}"></script>
<script src="{{ asset('build/js/apexcharts/line-chart-4.js') }}"></script>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert(`[Copy] Serial Transaksi : ${text}`);
        }).catch(err => {

            console.error("Could not copy text: ", err);
        });
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
