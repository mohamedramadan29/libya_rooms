@extends('admin.layouts.master')
@section('title')
    اصدار شهادة التوثيق
@endsection
@section('css')
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/css-rtl/custome_style.css') }}" rel="stylesheet">
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية </h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ اصدار شهادة التوثيق   </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <!-- row -->
    <div class="row row-sm">
        <!-- Col -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="mb-4 main-content-label"> اصدار شهادة التوثيق</div>
                </div>
                <div class="card-body">
                    <div class="certificate_head d-flex justify-content-between">
                        <img style="object-fit: contain" src="{{asset('assets/admin/certificate/header.png')}}" alt="">
{{--                        <div class="head1">--}}
{{--                            <h2> غرفة السياحة </h2>--}}
{{--                            <h2> المنطقة الغربية </h2>--}}
{{--                        </div>--}}
{{--                        <div class="image">--}}
{{--                            <img src="{{asset('assets/admin/img/logo_tabrat.png')}}">--}}
{{--                        </div>--}}
{{--                        <div class="head2">--}}

{{--                            <h3> دولة ليبيا </h3>--}}
{{--                            <h3> The State Of Libya </h3>--}}
{{--                        </div>--}}
                    </div>
                    <div class="main_head">
                        <img style="object-fit: contain;max-width: 50%;margin: auto;display: block;margin-top: 40px;margin-bottom: 30px;" src="{{asset('assets/admin/certificate/second_head.png')}}" alt="">
{{--                        <h1 class="text-center"> شهادة اثبات قيد بالغرفة </h1>--}}
                    </div>
                    <div class="content_body">
                        <p> بناءا علي طلب القيد المؤرخ في <span> </span> <span> /  </span> <span> </span>
                            <span> /  </span> <span> </span> عملاء باحكام المادة <br>
                            رقم (5) من القانون رقم (7) لسنة ( 2024 م ) بشان السياحة واللوائح والقرارات الصادرة بالخصوص .
                            فقد <br>
                            تم القيد بسجلات الغرفه تحت رقم ( <span> {{$company['id']}} </span> ) شعبة رقم (
                            <span> {{$company['category']['number']}} </span> )
                        </p>
                    </div>
                    <hr>
                    <div class="body_info">
                        <ul class="list-unstyled">
                            <li><span> الاسم التجاري </span> <span> {{$company['trade_name']}} </span></li>
                            <hr>
                            <li><span>  رئيس مجلس الادارة </span> <span> {{$company['name']}} </span></li>
                            <hr>
                            <li><span> اسم المدير العام </span> <span> {{$company['name']}} </span></li>
                            <hr>
                            <li><span> شكلها القانوني </span> <span> {{$company['name']}} </span></li>
                            <hr>
                            <li><span> عنوانها </span> <span> {{$company['address']}} </span></li>
                            <hr>
                            <li><span>  نوع النشاط </span> <span> {{$company['subcategory']['name']}} </span></li>
                            <hr>
                        </ul>
                        <div class="">
                            <img style="object-fit: contain" src="{{asset('assets/admin/certificate/head3.png')}}" alt="">
{{--                            <p> اعطيت هذة الشهادة لاستعمالها فيما يحولة القانون وصالحة لتجديد الرخصة واذن المزاولة </p>--}}
                        </div>
                    </div>
                    <div class="body_footer">
                        <div class="first_body">
                            <div>
                                @if($company['new_market_confirm_date'] == null)
                                    <p> حرر بتاريخ :   {{$company['first_market_confirm_date']}}   </p>
                                @else
                                    <p> حرر بتاريخ :   {{$company['new_market_confirm_date']}}      </p>
                                @endif

                            </div>

                            <div>
                                <p> تاريخ الصلاحية :  {{$expirationDate->format('Y-m-d')}}   </p>
                            </div>
                        </div>
                        <div>
                            <img style="    object-fit: contain;max-width: 90%;display: block;margin: auto;" src="{{asset('assets/admin/certificate/footer.png')}}" alt="">
                        </div>
{{--                        <div class="second_body">--}}
{{--                            <div class="sign">--}}
{{--                                <p> التوقيع المختص </p>--}}
{{--                                <p class="line"></p>--}}
{{--                            </div>--}}
{{--                            <div class="sign">--}}
{{--                                <p> المدير العام </p>--}}
{{--                                <p class="line"></p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="last_footer">--}}
{{--                            <div class="first">--}}
{{--                                <div>--}}
{{--                                    <span> <i class="fa fa-location-arrow"></i> </span>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <p> المكتب رقم 100 الدور الاول <br>--}}
{{--                                        برج طرابلس - طرابلس ليبيا <br>--}}
{{--                                        Office No - 100 First Floor <br>--}}
{{--                                        Triplo Tower - Triplo Libya--}}
{{--                                    </p>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                            <div class="first">--}}
{{--                                <div>--}}
{{--                                    <span> <i class="fa fa-phone"></i> </span>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <p> +218 91 731 0066 <br>--}}
{{--                                        +218 91 731 0066 <br>--}}
{{--                                        +218 91 731 0066 <br>--}}
{{--                                        +218 91 731 0066--}}
{{--                                    </p>--}}

{{--                                </div>--}}


{{--                            </div>--}}
{{--                            <div class="first">--}}
{{--                                <div>--}}
{{--                                    <span> <i class="fa fa-desktop"></i> </span>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <p> www.website.com <br>--}}
{{--                                        www.website.com--}}
{{--                                    </p>--}}

{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
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
