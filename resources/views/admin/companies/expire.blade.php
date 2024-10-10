@extends('admin.layouts.master')
@section('title')
    الشركات منتهية الصلاحية
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
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ الشركات منتهية الصلاحية     </span>
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
                    <h5>الشركات منتهية الصلاحية </h5>
                    <h6 class="bg-danger" style="color:#fff;padding:5px;border-radius: 5px;"> {{$expiredCount}}
                        شركة </h6>
                </div>
                <div class="card-body">
                    <div>
                        <p> فلترة الشركات منتهية الصلاحية حسب السنة والشهر </p>

                        <form method="GET" action="{{ url('admin/company/filter') }}">
                            <div class="d-flex align-items-center">
                                <div class="form_box" style="min-width: 30%">
                                    <label for="year" class="d-block"> حدد سنة الانتهاء </label>
                                    <select class="form-control" name="year" id="year">
                                        <option value="">اختر السنة</option>
                                        @for ($i = now()->year - 10; $i <= now()->year + 10; $i++)
                                            <option
                                                value="{{ $i }}" {{ old('year', request('year')) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form_box" style="min-width: 30%">
                                    <label for="month"> حدد الشهر </label>
                                    <select class="form-control" name="month" id="month">
                                        <option value="">اختر الشهر</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option
                                                value="{{ $i }}" {{old('month',request('month')) == $i ? 'selected':''}}>{{ $i }}</option>
                                        @endfor
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
                    <br>
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example">
                            <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0"> رقم الشركة</th>
                                <th class="wd-15p border-bottom-0"> الممثل القانوني</th>
                                <th class="wd-15p border-bottom-0"> تاريخ التوثيق</th>
                                <th class="wd-15p border-bottom-0"> الفترة</th>
                                <th class="wd-15p border-bottom-0"> تاريخ الانتهاء</th>
                                @if(\Illuminate\Support\Facades\Auth::user()->type=='admin' || Auth::user()->type =='money')
                                    <th class="wd-15p border-bottom-0"> المعاملات المالية</th>
                                @endif
                                <th class="wd-15p border-bottom-0"> التوثيق</th>
                                <th class="wd-15p border-bottom-0"> الدفع</th>
                                <th class="wd-15p border-bottom-0" id="column1"> العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach($companies as $company)
                                <tr>
                                    <td> {{$company['id']}} </td>
                                    <td> {{$company['name']}} </td>
                                    <td>
                                        @if($company['new_market_confirm_date'] !='')
                                            {{$company['new_market_confirm_date']}}
                                        @else
                                            {{$company['first_market_confirm_date']}}
                                        @endif  </td>
                                    <td> {{$company['isadarـduration']}} <span class="badge badge-danger"> سنة </span>
                                    </td>
                                    <td style="color:red"> {{ $company->expiry_date->format('Y-m-d') }} </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->type=='admin' || Auth::user()->type =='money')
                                        <td><a class="btn btn-info-gradient btn-sm"
                                               href="{{url('admin/company/transactions/'.$company['id'])}}">
                                                <i class="fa fa-money-bill"></i> </a></td>

                                    @endif
                                    <td>
                                        @if($company['market_confirm'] == 1)
                                            <span class="badge badge-success"> موثق  </span>
                                        @else
                                            <span class="badge badge-danger"> غير موثق  </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($company['money_confirm'] == 1)
                                            <span class="badge badge-success"> تم الدفع  </span>
                                            @if(\Illuminate\Support\Facades\Auth::user()->type == 'admin'||Auth::user()->type=='market')
                                                <a style="display: block;margin-top: 10px;"
                                                   href="{{url('admin/company/certificate/'.$company['id'])}}"
                                                   class="btn btn-info btn-sm"> اصدار شهادة القيد </a>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">  لم يتم الدفع   </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->type=='admin')
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
    <script src="{{asset('assets/admin/newdatatable/pdfmake.js')}}"></script>
    <script src="{{asset('assets/admin/newdatatable/vfs_fonts.js')}}"></script>
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
