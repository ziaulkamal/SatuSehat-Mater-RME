@php
$currentUrl = request()->url();
@endphp

<div class="center">
<div class="center-item">
    <div class="center-heading">Main Menu</div>
    <ul class="menu-list">
        <li class="menu-item{{ $currentUrl == route('dashboard') ? ' active' : '' }}">
            <a href="{{ route('dashboard') }}" class="{{ $currentUrl == route('dashboard') ? ' active' : '' }}">
                <div class="icon"><i class="icon-grid"></i></div>
                <div class="text">Dashboard</div>
            </a>
        </li>
        <li class="menu-item{{ $currentUrl == route('fasyankes') ? ' active' : '' }}">
            <a href="{{ route('fasyankes') }}" class="{{ $currentUrl == route('fasyankes') ? ' active' : '' }}">
                <div class="icon"><i class="icon-key"></i></div>
                <div class="text">Daftar Fasyankes</div>
            </a>
        </li>
        <li class="menu-item{{ $currentUrl == route('billing') ? ' active' : '' }}">
            <a href="{{ route('billing') }}" class="{{ $currentUrl == route('billing') ? ' active' : '' }}">
                <div class="icon"><i class="icon-shield"></i></div>
                <div class="text">Billing Due</div>
            </a>
        </li>
    </ul>
                    <div class="bot text-center" id="area-log-bottom">
                        <div class="wrap">

                            <span id="logout-mobile" class="tf-button w-full">Log Out</span>
                        </div>
                    </div>
</div>
</div>
