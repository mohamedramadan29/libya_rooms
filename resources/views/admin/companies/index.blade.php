@extends('admin.layouts.master')
@section('title')
    الشعب المسجلة
@endsection
@section('css')
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية </h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/  الشعب المسجلة   </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <!-- row -->
    <div class="row row-sm">
        <!-- Col -->
        <div class="col-lg-12">
            <div class="card">
                @if(Session::has('Success_message'))
                    <div
                        class="alert alert-success"> {{Session::get('Success_message')}} </div>
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
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5> الشعب المسجلة </h5>
                    @if(\Illuminate\Support\Facades\Auth::user()->type == 'admin'|| Auth::user()->type == 'supervisor')
                        <a href="{{url('admin/companies/store')}}" class="btn btn-primary btn-sm"> اضافة جديد
                            <i
                                class="fa fa-plus"></i> </a>
                    @endif
                </div>
                <div class="card-body">
                    <div>
                        <p> فلترة الشركات </p>
                        <form method="GET" action="{{ url('admin/company/main-filter')}}">
                            <div class="d-flex align-items-center">
                                <div class="form_box" style="min-width: 30%">
                                    <label for="category"> الشعبة </label>
                                    <select class="form-control" name="category" id="main_category">
                                        <option value=""> الكل</option>
                                        @foreach($categories as $category)
                                            <option
                                                value="{{$category['id']}}" {{ old('category',request('category')) == $category['id'] ? 'selected':'' }}> {{$category['name']}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form_box" style="min-width: 30%">
                                    <label for="category"> نوع النشاط </label>
                                    <select class="form-control" name="sub_category" id="sub_category">
                                        <option value=""> الكل</option>
                                    </select>
                                </div>
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script type="text/javascript">
                                    var selectedsubcategory = {{ old('sub_category') ?? 'null' }}; // الفرع الحالي للمستخدم
                                    // عندما تتغير المنطقة
                                    $('#main_category').on('change', function () {
                                        console.log('cliekc');
                                        var main_category = $(this).val();
                                        loadSubcategories(main_category, null); // تحميل الفروع بناءً على المنطقة
                                    });

                                    // دالة لتحميل الفروع
                                    function loadSubcategories(main_category, sub_category) {
                                        if (main_category) {
                                            $.ajax({
                                                url: '/admin/companies/get-subcategories/' + main_category,
                                                type: 'GET',
                                                dataType: 'json',
                                                success: function (data) {
                                                    $('#sub_category').empty();
                                                    $('#sub_category').append('<option value="">الكل</option>');
                                                    $.each(data, function (key, value) {
                                                        var selected = (sub_category && sub_category == value.id) ? 'selected' : '';
                                                        $('#sub_category').append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('#sub_category').empty();
                                            $('#sub_category').append('<option value=""> الكل </option>');
                                        }
                                    }

                                    // عند تحميل الصفحة، إذا كانت المنطقة محددة مسبقًا، استدعِ الفروع واضبط الفرع المختار
                                    $(document).ready(function () {
                                        var main_category = $('#main_category').val();
                                        if (main_category) {
                                            loadSubcategories(main_category, selectedsubcategory); // تحميل الفروع وتحديد الفرع الحالي
                                        }
                                    });
                                </script>


                                <div class="form_box" style="min-width: 30%">
                                    <label for="year" class="d-block"> تصنيف الشعبة </label>
                                    <select class="form-control" name="type" id="type">
                                        <option value=""> الكل</option>
                                        @foreach($types as $type)
                                            <option
                                                value="{{$type['id']}}" {{ old('type',request('type')) == $type['id'] ? 'selected':'' }}>{{$type['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form_box" style="min-width: 20%">
                                    <button style="margin-top: 29px" type="submit" class="btn btn-primary"><i
                                            class="fa fa-search"></i> تصفية
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example">
                            <thead>
                            <tr style="direction: rtl">
                                <th class="border-bottom-0"> اسم النشاط</th>
                                <th class="border-bottom-0"> نوع النشاط</th>
                                <th class="border-bottom-0"> التصنيف</th>
                                <th class="border-bottom-0"> تاريخ الانتهاء</th>
                                @if(\Illuminate\Support\Facades\Auth::user()->type=='admin' || Auth::user()->type=='supervisor' || Auth::user()->type =='money')
                                    <th class="wd-15p border-bottom-0"> المعاملات المالية</th>
                                @endif
                                <th class="border-bottom-0"> التوثيق</th>
                                <th class="border-bottom-0"> الدفع</th>
                                <th class="border-bottom-0" id="column1"> العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach($companies as $company)
                                <tr>
                                    <td style="direction: rtl;text-align: right"> {{$company['trade_name']}} </td>
                                    <td> {{$company['subcategory']['name']}} </td>
                                    <td> {{ optional($company->companytype)->name }} </td>
                                    <td>
                                        @if($company['first_market_confirm_date'] == null && $company['new_market_confirm_date'] == null)
                                            <span class="badge badge-danger"> لم يحدد بعد </span>
                                        @else
                                            {{ $company->expiry_date->format('Y-m-d') }}
                                        @endif
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->type=='admin' || Auth::user()->type=='supervisor'  || Auth::user()->type =='money')
                                        <td><a class="btn btn-info-gradient btn-sm"
                                               href="{{url('admin/company/transactions/'.$company['id'])}}">
                                                <i class="fa fa-money-bill"></i> </a></td>

                                    @endif
                                    <td>
                                        @if($company['market_confirm'] == 1)
                                            <span class="badge badge-success"> موثق  </span>
                                        @else
                                            @if(Auth::user()->type == 'supervisor')
                                                <button data-target="#market_confirm_{{$company['id']}}"
                                                        data-toggle="modal" class="btn btn-success btn-sm"><i
                                                        class="fa fa-check"> توثيق الشركه </i>
                                                </button>
                                            @endif
                                            <span class="badge badge-danger"> غير موثق  </span>

                                        @endif
                                    </td>
                                    <td>
                                        @if($company['money_confirm'] == 1)
                                            <span class="badge badge-success"> تم الدفع  </span>
                                            @if(\Illuminate\Support\Facades\Auth::user()->type == 'admin' || Auth::user()->type=='supervisor' ||Auth::user()->type=='market')
                                                <a style="display: block;margin-top: 10px;"
                                                   href="{{url('admin/company/certificate/'.$company['id'])}}"
                                                   class="btn btn-info btn-sm"> اصدار شهادة القيد </a>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">  لم يتم الدفع   </span>
                                            @if(Auth::user()->type == 'supervisor')
                                                <button data-target="#money_confirm_{{$company['id']}}"
                                                        data-toggle="modal" class="btn btn-success btn-sm"><i
                                                        class="fa fa-check"> تأكيد الدفع </i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->type=='admin' || Auth::user()->type=='supervisor' )
                                            <a href="{{url('admin/companies/update/'.$company['id'])}}"
                                               class="btn btn-primary btn-sm"> <i class="fa fa-edit"></i> </a>
                                            <button data-target="#delete_model_{{$company['id']}}"
                                                    data-toggle="modal" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-trash"></i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->type == 'market' && $company['market_confirm'] !=1)
                                            <button data-target="#market_confirm_{{$company['id']}}"
                                                    data-toggle="modal" class="btn btn-success btn-sm"><i
                                                    class="fa fa-check"> توثيق الشركه </i>
                                            </button>
                                        @endif

                                        @if(Auth::user()->type == 'money' && $company['money_confirm'] !=1)
                                            <button data-target="#money_confirm_{{$company['id']}}"
                                                    data-toggle="modal" class="btn btn-success btn-sm"><i
                                                    class="fa fa-check"> تأكيد الدفع </i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @include('admin.companies.delete')
                                @include('admin.companies.market_confirm_company')
                                @include('admin.companies.money_confirm_company')

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div>

        </div>
    </div>
    <!-- /Col -->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/jszip.min.js') }}"></script>
    {{--    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/pdfmake.min.js') }}"></script>--}}
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>--}}
    {{--    <script src="{{asset('vfs_fonts.js')}}"></script>--}}
    <script src="{{asset('assets/admin/newdatatable/pdfmake.js')}}"></script>
    <script src="{{asset('assets/admin/newdatatable/vfs_fonts.js')}}"></script>
    {{--    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/vfs_fonts.js') }}"></script>--}}
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/admin/js/table-data.js') }}"></script>
@endsection



<!-- تضمين jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // عند النقر على زر تغيير حالة الشحنات
        $('.change_orders_status2').on('click', function () {
            var selectedOrders = [];
            // الحصول على الطلبات المحددة
            $('input[name="select_row"]:checked').each(function () {
                selectedOrders.push($(this).val());
            });
            // تحديث قيمة حقل الإدخال الخفي
            $('.selected_orders').val(selectedOrders.join(','));

            // إرسال الطلبات المحددة إلى الخادم
            $('.change_orders_status_form').submit();
        });
        // تحديد الكل
        document.getElementById("order_check_select_all").addEventListener("change", function () {
            var checkboxes = document.querySelectorAll('.order_check_single');
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = document.getElementById("order_check_select_all").checked;
            });
        });

        // إلغاء تحديد الكل عند إلغاء تحديد أحد الـ checkbox
        document.querySelectorAll('.order_check_single').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var allChecked = true;
                document.querySelectorAll('.order_check_single').forEach(function (checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });
                document.getElementById("order_check_select_all").checked = allChecked;
            });
        });

    });
</script>
