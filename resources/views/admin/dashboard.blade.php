@extends('admin.layouts.master')
@section('title')
    الرئيسية
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                @if (\Illuminate\Support\Facades\Auth::user()->type == 'admin')
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1"> الادمن العام </h2>
                @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'supervisor')
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1"> لوحة تحكم المشرف </h2>
                @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'money')
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">مرحبا , {{ Auth::user()->name }} </h2>
                @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'market')
                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">مرحبا , {{ Auth::user()->name }} </h2>
                @endif
                {{--                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">مرحبا , {{Auth::user()->name}} </h2> --}}
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
        @if (\Illuminate\Support\Facades\Auth::user()->type == 'admin')
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> عدد الشعب الكلي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php echo count(\App\Models\admin\Companies::all()) @endphp </h4>
                                    <a href="{{ url('admin/companies') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
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
                            <h6 class="mb-3 tx-17 text-white"> المعاملات المالية </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php  echo count(\App\Models\admin\FinanialTransaction::all()) @endphp </h4>
                                    <a href="{{ url('admin/transaction') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                @php
                    $user = \Illuminate\Support\Facades\Auth::user();
                    // بناء الاستعلام الأساسي بناءً على نوع المستخدم
                    if ($user->type == 'admin') {
                        $query = \App\Models\admin\Companies::query();
                    } elseif ($user->type == 'supervisor') {
                        $query = \App\Models\admin\Companies::where('region', $user->regions);
                        if ($user->branches !== null) {
                            $query->where('branch', $user->branches);
                        }
                    }

                    // فلترة الشركات التي انتهت صلاحيتها باستخدام تواريخ التوثيق ومدة الإيداع
                    $query->where(function ($subQuery) {
                        // حساب تاريخ انتهاء صلاحية الشركة بناءً على تاريخ التوثيق الأول أو الجديد
                        $subQuery
                            ->where(function ($query) {
                                $query
                                    ->whereRaw(
                                        'DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR) < NOW()',
                                    )
                                    ->whereNull('new_market_confirm_date');
                            })
                            ->orWhere(function ($query) {
                                $query->whereRaw(
                                    'DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR) < NOW()',
                                );
                            });
                    });

                    // جلب الشركات المفلترة والمنتهية الصلاحية
                    $companies = $query->orderBy('id', 'desc')->get();
                    $expiredCount = $companies->count();
                @endphp
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> شعب منتهية الصلاحية </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> {{ $expiredCount }} </h4>
                                    <a href="{{ url('admin/expire-companies') }}" class="mb-0 tx-17 text-white op-7">
                                        مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12"> --}}
            {{--                <div class="card overflow-hidden sales-card bg-success-gradient"> --}}
            {{--                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0"> --}}
            {{--                        <div class=""> --}}
            {{--                            <h6 class="mb-3 tx-17 text-white"> عدد القيود المسجلة </h6> --}}
            {{--                        </div> --}}
            {{--                        <div class="pb-0 mt-0"> --}}
            {{--                            <div class="d-flex"> --}}
            {{--                                <div class=""> --}}
            {{--                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> 1 </h4> --}}
            {{--                                    <a href="{{url('admin/companies')}}" class="mb-0 tx-12 text-white op-7"> مشاهدة --}}
            {{--                                        التفاصيل </a> --}}
            {{--                                </div> --}}
            {{--                            </div> --}}
            {{--                        </div> --}}
            {{--                    </div> --}}

            {{--                </div> --}}
            {{--            </div> --}}

            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                @php
                    // فلترة الشركات التي تنتهي صلاحيتها خلال الشهر الحالي
                    $user = \Illuminate\Support\Facades\Auth::user();

                    // بناء الاستعلام الأساسي بناءً على نوع المستخدم
                    if ($user->type == 'admin') {
                        $query = \App\Models\admin\Companies::query();
                    } elseif ($user->type == 'supervisor') {
                        $query = \App\Models\admin\Companies::where('region', $user->regions);
                        if ($user->branches !== null) {
                            $query->where('branch', $user->branches);
                        }
                    }

                    // فلترة الشركات التي تنتهي صلاحيتها خلال الشهر الحالي
                    $query->where(function ($subQuery) {
                        // حساب تاريخ انتهاء صلاحية الشركة بناءً على تاريخ التوثيق الأول أو الجديد
                        $subQuery
                            ->where(function ($query) {
                                $query
                                    ->whereRaw(
                                        'DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)',
                                    )
                                    ->whereNull('new_market_confirm_date');
                            })
                            ->orWhere(function ($query) {
                                $query->whereRaw(
                                    'DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)',
                                );
                            });
                    });

                    // جلب الشركات المفلترة التي ستنتهي صلاحيتها خلال الشهر الحالي
                    $companies = $query->orderBy('id', 'desc')->get();

                    // حساب عدد الشركات التي ستنتهي صلاحيتها
                    $expiringCount = $companies->count();
                @endphp
                <div class="card overflow-hidden sales-card bg-info-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> قيود تنتهي خلال الشهر الحالي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> {{ $expiringCount }} </h4>
                                    <a href="{{ url('admin/expire-month') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
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
                            <h6 class="mb-3 tx-17 text-white"> شعب تحت المراجعه </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php

                                    @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php echo count(\App\Models\admin\Companies::where('active_status', 0)->get()) @endphp </h4>
                                    <a href="{{ url('admin/companies/company_under_view') }}"
                                        class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-purple-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> الشعب الغير موثقة </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php   $user = \Illuminate\Support\Facades\Auth::user(); @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php
                                        echo count(
                                            \App\Models\admin\Companies::where('market_confirm', '0')
                                                // ->where('region', $user->regions)
                                                // ->where('branch', $user->branches)
                                                ->where('active_status', 1)
                                                ->orderby('id', 'desc')
                                                ->get(),
                                        );
                                    @endphp </h4>
                                    <a href="{{ url('admin/companies/market-unconfirmed') }}"
                                        class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------ SuperVisor ----------------->
        @elseif(Auth::user()->type == 'supervisor')
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> عدد الشعب الكلي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php
                                        $user = \Illuminate\Support\Facades\Auth::user();
                                        $query = \App\Models\admin\Companies::where('region', $user->regions);
                                        // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
                                        if ($user->branches !== null) {
                                            $query->where('branch', $user->branches);
                                        }
                                        $companies = $query->get();
                                    @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php echo count($companies) @endphp </h4>
                                    <a href="{{ url('admin/companies') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
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
                            <h6 class="mb-3 tx-17 text-white"> المعاملات المالية </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php
                                        $query = \App\Models\admin\FinanialTransaction::with(
                                            'company_data',
                                            'employe_data',
                                        )->where('region', $user->regions);
                                        if ($user->branches !== null) {
                                            $query->where('branch', $user->branches);
                                        }
                                        $transactions = $query->get();
                                    @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php  echo count($transactions) @endphp </h4>
                                    <a href="{{ url('admin/transaction') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                @php
                    $user = \Illuminate\Support\Facades\Auth::user();

                    // بناء الاستعلام الأساسي بناءً على نوع المستخدم
                    if ($user->type == 'admin') {
                        $query = \App\Models\admin\Companies::query();
                    } elseif ($user->type == 'supervisor') {
                        $query = \App\Models\admin\Companies::where('region', $user->regions);
                        if ($user->branches !== null) {
                            $query->where('branch', $user->branches);
                        }
                    }

                    // فلترة الشركات التي انتهت صلاحيتها باستخدام تواريخ التوثيق ومدة الإيداع
                    $query->where(function ($subQuery) {
                        // حساب تاريخ انتهاء صلاحية الشركة بناءً على تاريخ التوثيق الأول أو الجديد
                        $subQuery
                            ->where(function ($query) {
                                $query
                                    ->whereRaw(
                                        'DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR) < NOW()',
                                    )
                                    ->whereNull('new_market_confirm_date');
                            })
                            ->orWhere(function ($query) {
                                $query->whereRaw(
                                    'DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR) < NOW()',
                                );
                            });
                    });

                    // جلب الشركات المفلترة والمنتهية الصلاحية
                    $companies = $query->orderBy('id', 'desc')->get();
                    $expiredCount = $companies->count();
                @endphp
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> شعب منتهية الصلاحية </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> {{ $expiredCount }} </h4>
                                    <a href="{{ url('admin/expire-companies') }}" class="mb-0 tx-17 text-white op-7">
                                        مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                @php
                    // فلترة الشركات التي تنتهي صلاحيتها خلال الشهر الحالي
                    $user = \Illuminate\Support\Facades\Auth::user();

                    // بناء الاستعلام الأساسي بناءً على نوع المستخدم
                    if ($user->type == 'admin') {
                        $query = \App\Models\admin\Companies::query();
                    } elseif ($user->type == 'supervisor') {
                        $query = \App\Models\admin\Companies::where('region', $user->regions);
                        if ($user->branches !== null) {
                            $query->where('branch', $user->branches);
                        }
                    }

                    // فلترة الشركات التي تنتهي صلاحيتها خلال الشهر الحالي
                    $query->where(function ($subQuery) {
                        // حساب تاريخ انتهاء صلاحية الشركة بناءً على تاريخ التوثيق الأول أو الجديد
                        $subQuery
                            ->where(function ($query) {
                                $query
                                    ->whereRaw(
                                        'DATE_ADD(first_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)',
                                    )
                                    ->whereNull('new_market_confirm_date');
                            })
                            ->orWhere(function ($query) {
                                $query->whereRaw(
                                    'DATE_ADD(new_market_confirm_date, INTERVAL isadarـduration YEAR) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 MONTH)',
                                );
                            });
                    });

                    // جلب الشركات المفلترة التي ستنتهي صلاحيتها خلال الشهر الحالي
                    $companies = $query->orderBy('id', 'desc')->get();

                    // حساب عدد الشركات التي ستنتهي صلاحيتها
                    $expiringCount = $companies->count();
                @endphp
                <div class="card overflow-hidden sales-card bg-info-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> قيود تنتهي خلال الشهر الحالي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> {{ $expiringCount }} </h4>
                                    <a href="{{ url('admin/expire-month') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
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
                            <h6 class="mb-3 tx-17 text-white"> شعب تحت المراجعه </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php
                                        $query = \App\Models\admin\Companies::where('region', $user->regions);
                                        // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
                                        if ($user->branches !== null) {
                                            $query->where('branch', $user->branches);
                                        }
                                        $companies = $query->where('active_status', 0)->get();
                                    @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php echo count($companies) @endphp </h4>
                                    <a href="{{ url('admin/companies/company_under_view') }}"
                                        class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-purple-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> الشعب الغير موثقة </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php
                                    $query = \App\Models\admin\Companies::where('region', $user->regions);
                                    // إذا كان لدى المشرف فرع معين، أضف شرط الفرع
                                    if ($user->branches !== null) {
                                        $query->where('branch', $user->branches);
                                    }
                                    $companies = $query->where('active_status', 1)->where('market_confirm',0)->get();
                                @endphp

                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php
                                        echo count($companies);
                                    @endphp </h4>
                                    <a href="{{ url('admin/companies/market-unconfirmed') }}"
                                        class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'market')
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> عدد الشعب الكلي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php   $user = \Illuminate\Support\Facades\Auth::user(); @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php
                                        echo count(
                                            \App\Models\admin\Companies::orderby('id', 'desc')
                                                ->where('region', $user->regions)
                                                ->where('branch', $user->branches)
                                                ->get(),
                                        );
                                    @endphp </h4>
                                    <a href="{{ url('admin/companies') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
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
                            <h6 class="mb-3 tx-17 text-white"> الشعب الغير موثقة </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php   $user = \Illuminate\Support\Facades\Auth::user(); @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php
                                        echo count(
                                            \App\Models\admin\Companies::where('market_confirm', '0')
                                            ->where('active_status', 1)
                                                ->where('region', $user->regions)
                                                ->where('branch', $user->branches)
                                                ->orderby('id', 'desc')
                                                ->get(),
                                        );
                                    @endphp </h4>
                                    <a href="{{ url('admin/companies/market-unconfirmed') }}"
                                        class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(\Illuminate\Support\Facades\Auth::user()->type == 'money')
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> عدد الشعب الكلي </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php   $user = \Illuminate\Support\Facades\Auth::user(); @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php
                                        echo count(
                                            \App\Models\admin\Companies::where('market_confirm', '1')
                                                ->where('region', $user->regions)
                                                ->where('branch', $user->branches)
                                                ->get(),
                                        );
                                    @endphp </h4>
                                    <a href="{{ url('admin/companies') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
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
                            <h6 class="mb-3 tx-17 text-white"> شعب لم يتم تأكيدها </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php   $user = \Illuminate\Support\Facades\Auth::user(); @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php
                                        echo count(
                                            \App\Models\admin\Companies::where('money_confirm', '0')
                                                ->where('market_confirm', '1')
                                                ->where('region', $user->regions)
                                                ->where('branch', $user->branches)
                                                ->get(),
                                        );
                                    @endphp </h4>
                                    <a href="{{ url('admin/companies/money-unconfirmed') }}"
                                        class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                        <div class="">
                            <h6 class="mb-3 tx-17 text-white"> المعاملات المالية </h6>
                        </div>
                        <div class="pb-0 mt-0">
                            <div class="d-flex">
                                <div class="">
                                    @php   $user = \Illuminate\Support\Facades\Auth::user(); @endphp
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white"> @php
                                        echo count(
                                            \App\Models\admin\FinanialTransaction::where('region', $user->regions)
                                                ->where('branch', $user->branches)
                                                ->get(),
                                        );
                                    @endphp </h4>
                                    <a href="{{ url('admin/transaction') }}" class="mb-0 tx-17 text-white op-7"> مشاهدة
                                        التفاصيل </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
    <!-- row closed -->

    </div>
    </div>
    <!-- Container closed -->
@endsection
