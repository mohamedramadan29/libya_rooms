@extends('admin.layouts.master')
@section('title')
    المعاملات المالية
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
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/    المعاملات المالية  </span>
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
                    <div class="mb-4 main-content-label"> ادارة المعاملات المالية</div>
                    <div class="card-header">
                        <a href="{{url('admin/transaction/store')}}" class="btn btn-primary btn-sm"> اضف عملية جديدة <i
                                class="fa fa-plus"></i> </a>
                    </div>
                    <!-- Add New Section -->

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table text-md-nowrap" id="example2">
                                <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0"> رقم الايصال</th>
                                    <th class="wd-15p border-bottom-0"> اسم الشركة</th>
                                    <th class="wd-15p border-bottom-0"> تاريخ المعاملة</th>
                                    <th class="wd-20p border-bottom-0"> الموطف</th>
                                    <th class="wd-20p border-bottom-0"> القيد</th>
                                    <th class="wd-20p border-bottom-0"> التجديد</th>
                                    <th class="wd-20p border-bottom-0"> التصديق</th>
                                    <th class="wd-20p border-bottom-0"> الشهادات</th>
                                    <th class="wd-15p border-bottom-0"> العمليات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                    $total_new = 0;
                                    $total_renew = 0;
                                    $total_confirm = 0;
                                    $total_certificate = 0;
                                @endphp
                                @foreach($transactions as $trans)
                                    <tr>
                                        <td> {{$trans['trans_number']}} </td>
                                        <td><a href="{{url('admin/company/transactions/'.$trans['company_data']['id'])}}"> {{$trans['company_data']['name']}}  </a></td>
                                        <td> {{$trans['created_at']}} </td>
                                        <td> {{$trans['employe_data']['name']}} </td>
                                        <td>
                                            @if($trans['trans_type'] == 'القيد')
                                                {{$trans['trans_price']}}
                                                @php $total_new = $total_new + $trans['trans_price']; @endphp
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($trans['trans_type'] == 'التجديد')
                                                {{$trans['trans_price']}}
                                                @php $total_renew = $total_renew + $trans['trans_price']; @endphp
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($trans['trans_type'] == 'التصديق')
                                                {{$trans['trans_price']}}
                                                @php $total_confirm = $total_confirm + $trans['trans_price']; @endphp
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            @if($trans['trans_type'] == 'الشهادات')
                                                {{$trans['trans_price']}}
                                                @php $total_certificate = $total_certificate + $trans['trans_price']; @endphp
                                            @else
                                                0
                                            @endif
                                        </td>

                                        @if(\Illuminate\Support\Facades\Auth::user()->type=='admin' || Auth::user()->type =='money')
                                        <td>
                                            <a href="{{url('admin/transaction/update/'.$trans['id'])}}"
                                               class="bn btn-primary btn-sm"> <i class="fa fa-edit"></i> </a>

                                            <button data-target="#delete_model_{{$trans['id']}}"
                                                    data-toggle="modal" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                        @endif
                                    </tr>

                                    <!-- Delete Section Model  -->
                                    @include('admin.finanial_transaction.delete')
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4"> الاجمالي </th>
                                    <th> {{ $total_new  }} </th>
                                    <th> {{ $total_renew  }} </th>
                                    <th> {{ $total_confirm  }} </th>
                                    <th> {{ $total_certificate  }} </th>
                                    <th> </th>
                                </tr>
                                </tfoot>
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
