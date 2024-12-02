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
    <div class="breadcrumb-header justify-content-between no-print">
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
            <div class="card" style="background-color:#fff !important;">
                <div class="card-header no-print d-flex align-items-center justify-content-between">
                    <div class="mb-4 main-content-label"> اصدار شهادة التوثيق</div>
                    <button onclick="printDiv('print')" class="btn btn-primary">طباعة الشهادة</button>
                </div>
                <div class="card-body print" id="print">
                    <div class="certificate_head d-flex justify-content-between">
                        <img class="no-print" style="object-fit: contain"
                             src="{{asset('assets/admin/certificate/header.png')}}" alt="">
                    </div>

                    <div class="main_head no-print">
                        <img
                            style="object-fit: contain;max-width: 50%;margin: auto;display: block;margin-top: 40px;margin-bottom: 30px;"
                            src="{{asset('assets/admin/certificate/second_head.png')}}" alt="">
                    </div>
                    <div class="content_body">
                        <div>
                            <p> <strong>  بناءا علي طلب القيد المؤرخ في </strong> <span class="text1"> </span> <span> </span> <span> /  </span>
                                <span> </span>
                                <span> /  </span> <span> </span>   <strong>  عملاء باحكام المادة </strong><br>
                               <strong>  رقم (5) من القانون رقم (7) لسنة ( 2024 م ) بشان السياحة واللوائح والقرارات الصادرة
                                   بالخصوص .
                                   فقد  </strong>
                                <br>
                                <strong> تم القيد بسجلات الغرفه تحت رقم ( </strong>  <span class="text2"> {{$company['id']}} </span> <strong> ) شعبة رقم ( </strong>
                                <span class="text3"> {{$company['categorydata']['number']}} </span> <strong>  ) </strong>
                            </p>
                        </div>

                    </div>
                    <hr>
                    <div class="body_info">
                        <ul class="list-unstyled listed_info">
                            <li><span class="no-print"> الاسم التجاري </span> <span> {{$company['trade_name']}} </span>
                            </li>
                            <hr>
                            <li><span class="no-print">  رئيس مجلس الادارة </span> <span> {{$company['name']}} </span>
                            </li>
                            <hr>
                            <li><span class="no-print"> اسم المدير العام </span> <span> {{$company['name']}} </span>
                            </li>
                            <hr>
                            <li><span class="no-print"> شكلها القانوني </span> <span> {{ $type_name }}  </span></li>
                            <hr>
                            <li><span class="no-print"> عنوانها </span> <span> {{$company['address']}} </span></li>
                            <hr>
                            <li><span class="no-print">  نوع النشاط </span>
                                <span> {{$company['subcategory']['name']}} </span></li>
                            <hr>
                        </ul>

                        <div class="">
                            <img class="no-print" style="object-fit: contain"
                                 src="{{asset('assets/admin/certificate/head3.png')}}"
                                 alt="">
                            {{--                            <p> اعطيت هذة الشهادة لاستعمالها فيما يحولة القانون وصالحة لتجديد الرخصة واذن المزاولة </p>--}}
                        </div>
                    </div>
                    <div class="body_footer">
                        <div class="first_body">
                            <div>
                                @if($company['new_market_confirm_date'] == null)
                                    <p class="text4">  <strong class="no-print text4"> حرر بتاريخ : </strong> {{$company['first_market_confirm_date']}}   </p>
                                @else
                                    <p class="text4">  <strong class="no-print text4"> حرر بتاريخ : </strong>   {{$company['new_market_confirm_date']}}      </p>
                                @endif

                            </div>

                            <div>
                                <p class="text5"><strong class="no-print">  تاريخ الصلاحية :  </strong> {{$expirationDate->format('Y-m-d')}}   </p>
                            </div>
                        </div>
                        <div class="no-print">
                            <img class="no-print"
                                 style="object-fit: contain;max-width: 90%;display: block;margin: auto;"
                                 src="{{asset('assets/admin/certificate/footer.png')}}" alt="">
                        </div>
                        <style>
                            @media print {
                                .content_body {
                                    padding-top: 250px;
                                }

                                .listed_info {
                                    padding-bottom: 80px;
                                }

                                .no-print {
                                    display: none;
                                }
                                .text1{
                                    display: inline-block;
                                }
                                .text2{
                                    display: inline-block;

                                }
                                .text4{
                                    margin-right: 55px;
                                }
                                .text5{
                                    margin-right: 55px;
                                }
                            }
                        </style>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Col -->
    </div>

    <style>
        body {
            background-color: #fff;
        }
    </style>
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

    })

    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // لإعادة تحميل الصفحة بعد الطباعة
    }

</script>


