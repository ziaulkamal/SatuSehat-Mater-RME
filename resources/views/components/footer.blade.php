
                        <div class="bottom-page">
                            <div class="body-text">Copyright © 2024 {{ env('APP_NAME') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('build/js/jquery.min.js') }}"></script>
    <script src="{{ asset('build/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('build/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('build/js/zoom.js') }}"></script>
    <script src="{{ asset('build/js/theme-settings.js') }}"></script>
    <script src="{{ asset('build/js/main.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var areaLogBottom = document.getElementById('area-log-bottom');
            if (window.innerWidth < 768) {
                areaLogBottom.style.display = 'block';
            } else {
                areaLogBottom.style.display = 'none';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logout = document.getElementById('logout');
            const logoutMobile = document.getElementById('logout-mobile');

            logout.addEventListener('click', function(event) {
                var verified = confirm('Apakah yakin ingin logout ?')
                if (verified) {
                    logoutGo();
                }
             })

            logoutMobile.addEventListener('click', function(event) {
                var verified = confirm('Apakah yakin ingin logout ?')
                if (verified) {
                    logoutGo();
                }
             })
        });

        function logoutGo() {
            $.ajax({
                url: `{{ route('logout') }}`,
                method: 'POST',
                success: function (response) {
                    if (response.success) {
                        alert(response.message)
                        window.location.href = response.redirect;
                    }
                },
                error: function (xhr, status, error) {
                    console.error('error: ', error);
                }
            });
        }
    </script>
    @yield('footerScript')
</body>

</html>
