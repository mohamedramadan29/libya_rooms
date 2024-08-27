@extends('admin.layouts.master')
@section('title')
    الشركات المسجلة
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
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ الشركات المسجلة   </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <!-- row -->
    <div class="row row-sm">
        <!-- Col -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
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
                    <div class="mb-4 main-content-label"> الشركات المسجلة</div>
                    <div class="card-header d-flex align-items-center">
                        <a href="{{url('admin/companies/store')}}" class="btn btn-primary btn-sm"> اضافة شركة جديدة <i
                                class="fa fa-plus"></i> </a>
                        @if(\Illuminate\Support\Facades\Auth::user()->type == 'market')
                            <form style="margin-right: 10px;margin-left: 10px;margin-bottom: 0" method="post"
                                  class="change_orders_status_form"
                                  action="{{url('admin/companies/market_confirm')}}">
                                @csrf
                                <input type="hidden" name="companies" id="selected_orders" class="selected_orders"
                                       value="">
                                <button type="submit" class="btn btn-info btn-sm change_orders_status2">
                                    تاكيد توثيق الشركات <i
                                        class="fa fa-check"></i>
                                </button>
                            </form>
                        @endif

                        @if(\Illuminate\Support\Facades\Auth::user()->type == 'money')
                            <form style="margin-right: 10px;margin-left: 10px;margin-bottom: 0" method="post"
                                  class="change_orders_status_form"
                                  action="{{url('admin/companies/money_confirm')}}">
                                @csrf
                                <input type="hidden" name="companies" id="selected_orders" class="selected_orders"
                                       value="">
                                <button type="submit" class="btn btn-warning btn-sm change_orders_status2">
                                    تاكيد علي الدفع <i
                                        class="fa fa-money-bill"></i>
                                </button>
                            </form>
                        @endif

                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table text-md-nowrap" id="example2">
                                <thead>
                                <tr>
                                    @if(Auth::user()->type == 'market' || Auth::user()->type == 'money')
                                        <th>
                                            <input style="cursor: pointer" id="order_check_select_all"
                                                   type="checkbox"
                                                   name="select_all">
                                        </th>
                                    @endif

                                    <th class="wd-15p border-bottom-0"> رقم الشركة</th>
                                    <th class="wd-15p border-bottom-0"> اسم الممثل القانوني</th>
                                    <th class="wd-15p border-bottom-0"> تاريخ الميلاد</th>
                                    <th class="wd-15p border-bottom-0"> الرقم الوطني</th>
                                    <th class="wd-15p border-bottom-0"> محل الاقامة</th>
                                    <th class="wd-15p border-bottom-0"> المعاملات المالية</th>
                                    <th class="wd-15p border-bottom-0"> حالة التوثيق</th>
                                    <th class="wd-15p border-bottom-0"> تاكيد الدفع</th>
                                    <th class="wd-15p border-bottom-0" id="column1"> العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($companies as $company)
                                    <tr>
                                        @if(Auth::user()->type == 'market' || Auth::user()->type == 'money' )
                                            <td><input style="cursor: pointer" id="order_check_select"
                                                       class="order_check_single" type="checkbox"
                                                       name="select_row" value="{{$company['id']}}">

                                            </td>
                                        @endif
                                        <td> {{$company['id']}} </td>

                                        <td> {{$company['name']}} </td>
                                        <td> {{$company['birthdate']}} </td>
                                        <td> {{$company['id_number']}} </td>
                                        <td> {{$company['place']}} </td>
                                        <td><a class="btn btn-info-gradient btn-sm"
                                               href="{{url('admin/company/transactions/'.$company['id'])}}"> المعاملات
                                                المالية </a></td>
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

                                                    <a style="display: block;margin-top: 10px;" href="{{url('admin/company/certificate/'.$company['id'])}}" class="btn btn-info btn-sm"> اصدار شهادة القيد  </a>
                                                @else
                                                    <span class="badge badge-danger">  لم يتم الدفع   </span>
                                                @endif
                                            </td>
                                        <td>
                                            <a href="{{url('admin/companies/update/'.$company['id'])}}"
                                               class="btn btn-primary btn-sm"> <i class="fa fa-edit"></i> </a>
                                            @if(\Illuminate\Support\Facades\Auth::user()->type=='admin')
                                                <button data-target="#delete_model_{{$company['id']}}"
                                                        data-toggle="modal" class="btn btn-danger btn-sm"><i
                                                        class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @include('admin.companies.delete')
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
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/plugins/datatable/js/vfs_fonts.js') }}"></script>
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
