@extends('admin.layouts.master')
@section('title')
    الرئيسية
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">مرحبا , {{Auth::user()->name}} </h2>
                <p class="mg-b-0"> لوحة التحكم الخاصة بك </p>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">

        <!--======================================================== Admin Dashboard ==================================================-->
        @if(\Illuminate\Support\Facades\Auth::user()->type == 'admin')
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white"> عدد الشركات الكلي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php echo count(\App\Models\admin\Companies::all()) @endphp  </h4>
                                    <a href="{{url('admin/companies')}}" class="mb-0 tx-12 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-danger-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white"> المعاملات المالية </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php  echo count(\App\Models\admin\FinanialTransaction::all()) @endphp </h4>
                                    <a href="{{url('admin/transaction')}}" class="mb-0 tx-12 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                @php
                    // فلترة الشركات التي انتهت صلاحيتها
                          $companies = \App\Models\admin\companies::whereRaw('DATE_ADD(isdar_date, INTERVAL isadarـduration YEAR) < NOW()')
                              ->orderBy('id', 'desc')
                              ->get();
                          // حساب عدد الشركات المنتهية
                          $expiredCount = $companies->count();
                @endphp
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white"> شركات منتهية الصلاحية </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> {{$expiredCount}} </h4>
                                    <a href="{{url('admin/expire-companies')}}" class="mb-0 tx-12 text-white op-7">
                                        مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white"> عدد القيود المسجلة </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> 1 </h4>
                                    <a href="#" class="mb-0 tx-12 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-info-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-12 text-white"> قيود تنتهي خلال الشهر الحالي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> 2 </h4>
                                    <a href="#" class="mb-0 tx-12 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'market')
            ادارة التوثيق
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'money')
            ادارة المالية
        @endif

    </div>
    <!-- row closed -->

    </div>
    </div>
    <!-- Container closed -->
@endsection

