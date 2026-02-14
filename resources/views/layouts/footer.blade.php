    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
            <span class="float-md-left d-block d-md-inline-block">Copyright  &copy; {{ date('Y') }} 
                <!-- <a class="text-bold-800 grey darken-2" href="https://banzaaratravels.com/" target="_blank">banz</a> -->
            </span>
            <span class="float-md-right d-none d-lg-block"><b>Version</b> 10.10</span>
        </p>
    </footer>
    <!-- END: Footer-->

    <script src="{{url('app-assets/vendors/js/vendors.min.js')}}"></script>
    <script src="{{url('app-assets/vendors/js/charts/apexcharts/apexcharts.min.js')}}"></script>    
    <script src="{{url('app-assets/js/core/app-menu.min.js')}}"></script>
    <script src="{{url('app-assets/js/core/app.min.js')}}"></script>
    <script src="{{url('app-assets/js/scripts/customizer.min.js')}}"></script>
    <script src="{{url('app-assets/js/scripts/cards/card-statistics.js')}}"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{URL::asset('public/app-assets/css/bootstrap.min.js')}}"></script> -->

    @yield('additionaljs')
    @yield('pagescript')
    </body>
</html>