@extends('layouts.app')

@section('content')
<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-xl-10 offset-1">
                    <div class="card mt-5" style="height: 400px;">
                        <div class="card-header">{{ __('Sign In to Continue') }}</div>
                        <form method="POST" action="{{ route('login') }}" class="mt-5">
                            @csrf
                            <div class="form-group row">
                                
                                <input id="site" type="hidden" value="{{site()}}">
                                <input id="dbname" type="hidden" value="{{dbname()}}">
                                <div class="col-md-8 offset-2">
                                    <label for="email" class="font-weight-bold col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-2">
                                    <label for="password" class="font-weight-bold col-form-label text-md-right">{{ __('Password') }}</label>

                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-2">
                                    <div class="col-md-6 offset-3">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            {{ __('Login') }}
                                        </button>
                                    </div>

                                    <div class="d-flex justify-content-between mt-3">
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                        <a class="btn btn-link" href="#">Help</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- end col-->
            </div>                        
        </div> <!-- end container-fluid -->
    </div> <!-- end content -->
</div>

<!--  Modal content for success notification -->
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" id="synchModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="myCenterModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center">
                <h6 class="">Some resources could not be loaded! <br/><br/> Check that you have an active internet connection and reload the page <br/><br/>If issue persists, contact support</h6>
                <button class="btn btn-info mt-4" onclick="window.location.href=''">Reload Page</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
@endsection

@section('footer')
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>

    <script type="text/javascript">

        window.addEventListener("load",getUsers,false);
        window.addEventListener("load",getAssignmentData,false);

        const synch_url = "https://demo.centinel.systems/centinel_owner/synch";

        const dbname = document.getElementById('dbname').value;

        const site = document.getElementById('site').value;

        function modal(){
            $('#synchModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }

        function getUsers(){
            let form = new FormData();
            let request = new XMLHttpRequest();
            request.open("GET",  synch_url+"/getusers?dbname="+dbname+"&update=userdata&site="+site, true);
            request.send(form);
            request.onload = function () {
                if (request.readyState === request.DONE) {
                    if (request.status === 200) {
                        console.log('getUsers=success');
                        console.log('getUsers='+request.response);
                        updateUsers(request.response);
                    }else{
                        console.log('getUsers='+request.response);
                        modal();
                    }
                }else{
                    console.log('getUsers='+request.response);
                    modal();
                }
            };
        }

        function updateUsers(response){
            let form = new FormData();
            form.append("_token", $('meta[name="csrf-token"]').attr('content'));
            form.append("new_data", response);
            let request = new XMLHttpRequest();
            request.open("POST",  "/attendant/updateusers", true);
            request.send(form);
            request.onload = function () {
                if (request.readyState === request.DONE) {
                    if (request.status === 200) {
                        console.log('updateUsers=success'+request.response);
                    }else{
                        console.log('updateUsers='+request.response);
                        modal();
                    }
                }else{
                    console.log('updateUsers='+request.response);
                    modal();
                }
            };
        }


        function getAssignmentData(){
            let form = new FormData();
            let request = new XMLHttpRequest();
            request.open("GET",  synch_url+"/getassignmentdata?dbname="+dbname+"&update=userdata&site="+site, true);
            request.send(form);
            request.onload = function () {
                if (request.readyState === request.DONE) {
                    if (request.status === 200) {
                        console.log('getAssignmentData=success');
                        console.log('getAssignmentData='+request.response);
                        updateAssignmentData(request.response);
                    }else{
                        console.log('getAssignmentData='+request.response);
                        modal();
                    }
                }else{
                    console.log('getAssignmentData='+request.response);
                    modal();
                }
            };
        }

        function updateAssignmentData(response){
            let form = new FormData();
            form.append("_token", $('meta[name="csrf-token"]').attr('content'));
            form.append("new_data", response);
            let request = new XMLHttpRequest();
            request.open("POST",  "/attendant/updateassignmentdata", true);
            request.send(form);
            request.onload = function () {
                if (request.readyState === request.DONE) {
                    if (request.status === 200) {
                        console.log('updateAssignmentData=success'+request.response);
                    }else{
                        console.log('updateAssignmentData='+request.response);
                        modal();
                    }
                }else{
                    console.log('updateAssignmentData='+request.response);
                    modal();
                }
            };
        }

        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script>

    <!--Morris Chart-->
    <script src="{{asset('assets/libs/morris-js/morris.min.js')}}"></script>
    <script src="{{asset('assets/libs/raphael/raphael.min.js')}}"></script>

    <!-- Dashboard init js-->
    <script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('assets/js/app.min.js')}}"></script>
@endsection