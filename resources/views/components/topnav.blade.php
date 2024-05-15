<div class="header-dashboard">
    <div class="wrap">
        <div class="header-left">
            <a href="{{ route('dashboard') }}">
                <h5>RME Panel</h5>
            </a>
            <div class="button-show-hide">
                <i class="icon-menu-left"></i>
            </div>

        </div>
        <div class="header-grid">

            <div class="header-item button-dark-light">
                <i class="icon-moon"></i>
            </div>
            <div class="header-item button-zoom-maximize">
                <div class="">
                    <i class="icon-maximize"></i>
                </div>
            </div>
            <div class="popup-wrap user type-header">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="header-user wg-user">
                            <span class="image">
                                <img src="{{ asset('build/images/avatar/user-1.png') }}" alt="">
                            </span>
                            <span class="flex flex-column">
                                <span class="body-title mb-2">Kristin Watson</span>
                                <span class="text-tiny">Admin</span>
                            </span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownMenuButton3" >
                        <li>
                            <a href="login.html" class="user-item">
                                <div class="icon">
                                    <i class="icon-log-out"></i>
                                </div>
                                <div class="body-title-2">Log out</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
