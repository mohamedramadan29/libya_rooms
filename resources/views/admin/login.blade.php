@extends('admin.layouts.master2')
@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{URL::asset('assets/admin/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}"
          rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The image half -->
            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent" style="background-color: #fff !important ">
                <div class="row wd-100p mx-auto text-center">
                    <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                        <img src="{{URL::asset('assets/admin/img/logo_tabrat.png')}}"
                             class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                    </div>
                </div>
            </div>
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">

                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            @if(\Illuminate\Support\Facades\Session::has('Error_Message'))
                                                <div class="alert alert-danger"> {{\Illuminate\Support\Facades\Session::get('Error_Message')}} </div>
                                            @endif

                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <h2>مرحبا!</h2>
                                            <h5 class="font-weight-semibold mb-4"> من فضلك سجل دخولك </h5>
                                            <form action="{{url('admin/admin_login')}}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label> رقم الهاتف  </label> <input placeholder="من فضلك ادخل رقم الهاتف" class="form-control"
                                                                                             name="phone" type="number"
                                                                                             required>
                                                </div>
                                                <div class="form-group">
                                                    <label>كلمه المرور</label> <input class="form-control"
                                                                                      name="password" type="password"
                                                                                      required>
                                                </div>
                                                <button class="btn btn-main-primary btn-block">تسجيل الدخول</button>
                                            </form>
                                            <div style="margin-top: 15px">

                                                <a href="{{url('companies/store')}}"> تسجيل نشاط جديد  </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>
@endsection
@section('js')
@endsection
