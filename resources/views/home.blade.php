@extends('layouts.header')

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
                    <div class="col-lg-12 col-xl-12">
                                
                        <div class="card" style="height: 170px;">

                            <span id="latestDataReset" style="display:none;"></span>
                            <span id="parkDataReset" style="display: none;"></span>
                            <input id="site" type="hidden" value="{{site()}}">
                            <input id="dbname" type="hidden" value="{{dbname()}}">
                            <input id="user_id" type="hidden" value="{{auth::user()->id}}">
                            <div class="card-header font-weight-bold" style="height:30px; padding-top:3px;">{{ __('Last Vehicle Out') }} <a href="/attendant/?"><button class="btn btn-primary" id="loadVehicle" style="position: absolute; margin-top: 3px; margin-left: 20px; display: none;">Load new vehicle</button></a></div>

                            <div class="p-3">
                                <table class="table">

                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold">Date</th>
                                            <th class="font-weight-bold">Time</th>
                                            <th class="font-weight-bold">Status Value</th>
                                            <th class="font-weight-bold">Duration</th>
                                            <th class="font-weight-bold">Image</th>
                                            <th class="font-weight-bold">Plate</th>
                                            <th class="font-weight-bold">Audit Score</th>
                                            <th class="font-weight-bold">Comment</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form action="/attendant/update" method="post">
                                            @csrf
                                            @method('PATCH')

                                            @if ($exit_count > 0)
                                                <tr>
                                                    <td>{{date("d.m.Y", $last_car->created_at)}}</td>
                                                    <td>{{date("H:i", $last_car->created_at)}}</td>
                                                    <td>
                                                        @if (is_numeric($last_car->audit))
                                                            R{{number_format($last_car->amount)}}
                                                        @else
                                                            <input class="form-control" style="width:150px;" value="{{$last_car->amount}}" required name="amount" type="number" autofocus>
                                                        @endif
                                                    </td>
                                                    <td>{{$last_car->duration}}</td>
                                                    <td style="">
                                                        <img src="https://www.team-bhp.com/forum/attachments/modifications-accessories/2014799d1591616982t-installed-front-parking-camera-my-honda-city-20200608_163116min.jpg" style="width:70px;">
                                                    </td>
                                                    <td>
                                                        @if ($last_car->status == 'open')
                                                            <input class="form-control" style="width:150px;" value="{{$last_car->plate}}" name="plate" required autofocus>
                                                        @else
                                                            {{$last_car->plate}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (is_numeric($last_car->audit))
                                                            {{$last_car->audit}}%
                                                        @else
                                                            {{$last_car->audit}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($last_car->comment == '')
                                                            <input class="form-control" style="width:150px;" value="{{$last_car->comment}}" required name="comment" autofocus>
                                                        @else
                                                            {{$last_car->comment}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="hidden" style="width:150px;" value="{{$last_car->plate}}" name="replate" required autofocus>
                                                        <input class="form-control" type="hidden" style="width:150px;" value="{{$last_car->parking_id}}" name="parking_id" required autofocus>
                                                        @if (is_numeric($last_car->audit))
                                                            
                                                        @else
                                                            <button class="btn btn-primary" style="color: black;">Assign</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <input type="hidden" id="latestParkingId" value="{{$last_car->parking_id}}" style="position: absolute;">
                                            @endif
                                        </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card"style="height: 350px;">
                            <div class="card-header font-weight-bold">{{ __('Vehicles Over the Last 24 Hours') }}</div>
                            <div style="height: 350px; overflow: auto;">
                                <div class="p-3">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="font-weight-bold">Date</th>
                                                <th class="font-weight-bold">Time</th>
                                                <th class="font-weight-bold">Status Value</th>
                                                <th class="font-weight-bold">Image</th>
                                                <th class="font-weight-bold">Plate</th>
                                                <th class="font-weight-bold">Audit Score</th>
                                                <th class="font-weight-bold">Comment</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="data">
                                            @foreach ($parked_items as $parked_item)
                                                <form action="/attendant/update" method="post">
                                                    @csrf
                                                    @method('PATCH')

                                                    <tr>
                                                        <td>{{date("d.m.Y", $parked_item->updated_at)}}</td>
                                                        <td>{{date("H:i", $parked_item->updated_at)}}</td>
                                                        <td>
                                                            @if ($parked_item->camera_type == 'exit')
                                                                @if (is_numeric($parked_item->audit))
                                                                    R{{number_format($parked_item->amount)}}
                                                                @else
                                                                    <input class="form-control" style="width:150px;" value="{{$parked_item->amount}}" required name="amount" type="number" autofocus>
                                                                @endif
                                                            @else
                                                                {{$parked_item->amount}}
                                                            @endif
                                                        </td>
                                                        <td style="">
                                                            <img src="https://www.team-bhp.com/forum/attachments/modifications-accessories/2014799d1591616982t-installed-front-parking-camera-my-honda-city-20200608_163116min.jpg" style="width:70px;">
                                                        </td>
                                                        <td>
                                                        @if (($parked_item->camera_type == 'exit') && ($parked_item->status == 'open'))
                                                            <input class="form-control" style="width:150px;" value="{{$parked_item->plate}}" required name="plate" autofocus>
                                                        @else
                                                            {{$parked_item->plate}}
                                                        @endif
                                                    </td>
                                                        <td>
                                                            @if (is_numeric($parked_item->audit))
                                                                {{$parked_item->audit}}%
                                                            @else
                                                                {{$parked_item->audit}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($parked_item->comment == '')
                                                                <input class="form-control" style="width:150px;" value="{{$parked_item->comment}}" required name="comment" autofocus>
                                                            @else
                                                                {{$parked_item->comment}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="hidden" style="width:150px;" value="{{$parked_item->plate}}" name="replate" required autofocus>
                                                            <input class="form-control" type="hidden" style="width:150px;" value="{{$parked_item->parking_id}}" name="parking_id" required autofocus>
                                                            @if ($parked_item->camera_type == 'exit')
                                                                @if (is_numeric($parked_item->audit))
                                                                    
                                                                @else
                                                                    <button class="btn btn-primary" style="color: black;">Assign</button>
                                                                @endif
                                                            @endif
                                                            
                                                        </td>
                                                    </tr>
                                                </form>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center col-12 mt-4 mb-3" id="nav">
                                    <!--<span><a class="font-weight-bold" href=""><i class="mdi mdi-chevron-double-left"></i></a></span>
                                    <span><a class="font-weight-bold" href=""><i class="mdi mdi-chevron-left"></i></a></span>-->
                                    <!--<span class="pag"><a class="font-weight-bold" href="">1</a></span>-->
                                    <!--<span><a class="font-weight-bold" href=""><i class="mdi mdi-chevron-right"></i></a></span>
                                    <span><a class="font-weight-bold" href=""><i class="mdi mdi-chevron-double-right"></i></a></span>-->
                                </div>
                            </div>
                            <div style="position: relative; bottom: 0; height: 4px; width:inherit; background-color: white; color:white;"> .</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
    <!-- Footer Start -->
    <!--<footer class="footer" style="padding-top: 10px; position: fixed; height: 50px; background-color: white; border-top: 1px solid #c4c4c4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary btn-small" style="color: black;">Update</button>
                    </div>
                </div>
            </div>
    </footer>-->
        <!-- end Footer -->
@endsection

@section('footer')
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>

    <script src="{{asset('assets/js/synch.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var rowsShown = 5;
            var rowsTotal = $('#data tr').length;
            var numPages = rowsTotal/rowsShown;
            for(i = 0;i < numPages;i++) {
                var pageNum = i + 1;
                $('#nav').append('<span class="pag"><a class="font-weight-bold" href="javascript:void()" rel="'+i+'">'+pageNum+'</a></span>');
            }
            $('#data tr').hide();
            $('#data tr').slice(0, rowsShown).show();
            $('#nav span:first').addClass('pag-active');
            $('#nav a').bind('click', function(){

                $('#nav span').removeClass('pag-active');
                $(this).parent().addClass('pag-active');
                var currPage = $(this).attr('rel');
                var startItem = currPage * rowsShown;
                var endItem = startItem + rowsShown;
                $('#data tr').css('opacity','0.0').hide().slice(startItem, endItem).
                css('display','table-row').animate({opacity:1}, 300);
            });
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