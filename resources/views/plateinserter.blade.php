@extends('layouts.headerinsert')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style type="text/css">
        .form-control{
            font-size: 13px;
        }
    </style>
     <div class="content-page">            
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-xl-12">

                        <div class="card" style="height: 150px; margin-top: 30px;">
                            <div class="card-header font-weight-bold" style="height:20px; padding-top:0;">{{ __('Last Vehicle Out') }}</div>
                            <div class="p-3">
                                <table class="table">
                                    <tbody>
                                        <form action="/attendant/addcamera" method="post">
                                            @csrf

                                            <tr>
                                                <td>
                                                    <label>Camera Name</label>
                                                   <input class="form-control" type="" style="width:150px;" value="" name="camera" required autofocus> 
                                                </td>
                                                <td>
                                                    <label>Camera Type</label>
                                                    <select class="form-control" type="" style="width:150px;" value="" name="camera_type" required autofocus>
                                                        <option></option>
                                                        <option value="index">Entrance</option>
                                                        <option value="exit">Exit</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <label>Floor Level</label>
                                                    <input class="form-control" type="number" style="width:150px;" value="" name="level" required autofocus>
                                                </td>
                                                <td>                
                                                    <button class="btn btn-primary" style="color: black; margin-top:30px;">Add Camera</button>
                                                </td>
                                            </tr>
                                        </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card"style="height: auto;">

                            <div class="card-header font-weight-bold">{{ __('Add Car Details') }}</div>
                            <form id="plateForm" action="/attendant/plateinserter/store" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group row">
                                    <div class="col-md-8 offset-2">
                                        <label for="plate" class="font-weight-bold col-form-label text-md-right">{{ __('Plate Number') }}</label>

                                        <input id="plate" type="plate" class="form-control @error('plate') is-invalid @enderror" name="plate" value="" minlength="5" required autocomplete="plate" autofocus>

                                        @error('plate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-8 offset-2">
                                        <label for="make" class="font-weight-bold col-form-label text-md-right">{{ __('Make') }}</label>

                                        <input id="make" type="make" class="form-control @error('make') is-invalid @enderror" name="make" value="" required autocomplete="make" autofocus>

                                        @error('make')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-8 offset-2">
                                        <label for="model" class="font-weight-bold col-form-label text-md-right">{{ __('Model') }}</label>

                                        <input id="model" type="model" class="form-control @error('model') is-invalid @enderror" name="model" value="" required autocomplete="model" autofocus>

                                        @error('model')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8 offset-2">
                                        <label for="color" class="font-weight-bold col-form-label text-md-right">{{ __('Color') }}</label>

                                        <input id="color" type="" class="form-control @error('color') is-invalid @enderror" name="color" value="" required autocomplete="color" autofocus>

                                        @error('color')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-8 offset-2">
                                        <label for="camera_type" class="font-weight-bold col-form-label text-md-right">{{ __('Camera Type') }}</label>

                                        <select id="camera_type" type="camera_type" class="form-control @error('camera_type') is-invalid @enderror" name="camera_type" value="" required autocomplete="camera_type" autofocus onchange="document.getElementById('camera').value=this.options[this.selectedIndex].text">
                                            <option selected disabled></option>
                                            @foreach ($cameras as $camera)
                                                <option value="{{$camera->camera_type}}">{{$camera->camera}}</option>
                                            @endforeach
                                        </select>

                                        <input type="hidden" name="camera" id="camera">

                                        @error('camera_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                

                                <br/>

                                <div class="form-group row">
                                    <div class="col-md-8 offset-2 text-center">
                                        <button type="submit" class="btn btn-success btn-block">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>

                                <span id="parkDataReset" style="display: none;"></span><br/>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>

    <script type="text/javascript">

        window.addEventListener("load",getUserData,false);
        window.addEventListener("load",displayUserData,false);

        const synch_url = "https://demo.centinel.systems/centinel_owner/synch";

        const dbname = document.getElementById('dbname').value;

        const site = document.getElementById('site').value;

        const user_id = document.getElementById('user_id').value;

        function getUserData(){
            let form = new FormData();
            form.append("question_no", "1");
            let request = new XMLHttpRequest();
            request.open("GET",  synch_url+"/getuserdata?dbname="+dbname+"&update=userdata&site="+site+"&user_id="+user_id, true);
            request.send(form);
            request.onload = function () {
                if (request.readyState === request.DONE) {
                    if (request.status === 200) {
                        console.log('getUserData='+request.response);
                        updateUserData(request.response);
                    }else{
                        console.log('getUserData='+request.response);
                    }
                }else{
                    console.log('getUserData='+request.response);
                }
            };
        }

        function updateUserData(response){
            var userData = JSON.parse(response);

            var mtd_income = "mtd_income";
            var mtd_income_value = JSON.stringify(userData[0].mtd_income);
            sessionStorage.setItem(mtd_income,mtd_income_value);

            var mtd_audit = "mtd_audit";
            var mtd_audit_value = JSON.stringify(userData[0].mtd_audit);
            sessionStorage.setItem(mtd_audit,mtd_audit_value);

            var mtdIncome = sessionStorage.getItem("mtd_income").replaceAll('"', '');
            var mtdAudit = sessionStorage.getItem("mtd_audit").replaceAll('"', '');
            document.getElementById('mtdIncome').innerHTML = Number(mtdIncome).toLocaleString();
            document.getElementById('mtdAudit').innerHTML = mtdAudit;

            /*
            for (var i=0; i<sessionStorage.length;i++) {
              var a = sessionStorage.key(i);
              var b = sessionStorage.getItem(a);
              console.log(a+'='+b);
            }
            */
        }

        function displayUserData(){
            if (sessionStorage.getItem("mtd_income") === null || sessionStorage.getItem("mtd_audit") === null ) {
                getUserData();
            }else{
                var mtdIncome = sessionStorage.getItem("mtd_income").replaceAll('"', '');
                var mtdAudit = sessionStorage.getItem("mtd_audit").replaceAll('"', '');
                document.getElementById('mtdIncome').innerHTML = Number(mtdIncome).toLocaleString();
                document.getElementById('mtdAudit').innerHTML = mtdAudit;
            }
        }

        function modal(){
            $('#synchModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }

        document.getElementById('logout').addEventListener("click",function(){
            sessionStorage.clear();
            window.location.href="/attendant/logout";
        });

        /*
        
        */
    </script>

    <!--Morris Chart-->
    <script src="{{asset('assets/libs/morris-js/morris.min.js')}}"></script>
    <script src="{{asset('assets/libs/raphael/raphael.min.js')}}"></script>

    <!-- Dashboard init js-->
    <script src="{{asset('assets/js/pages/dashboard.init.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('assets/js/app.min.js')}}"></script>
@endsection