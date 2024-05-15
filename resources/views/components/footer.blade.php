
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
    @yield('footerScript')
</body>

</html>
