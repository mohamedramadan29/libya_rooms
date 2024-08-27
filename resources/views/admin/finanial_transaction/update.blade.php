
@extends('admin.layouts.master')
@section('title')
    تعديل معاملة مالية
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">   تعديل معاملة مالية   </span>
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
                    @if (Session::has('Success_message'))
                        <div class="alert alert-success"> {{ Session::get('Success_message') }} </div>
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

                    <form class="form-horizontal" method="post" action="{{ url('admin/transaction/update/'.$transaction['id']) }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="mb-4  btn btn-sm btn-success-gradient light-text">  تعديل معاملة مالية  </div>
                                <div class="form-group ">
                                    <label class="form-label">  رقم الايصال : </label>
                                    <input required type="text" class="form-control" name="trans_number" value="{{$transaction['trans_number']}}">
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">  قيمة المعاملة  : </label>
                                    <input required type="number" class="form-control" name="trans_price" value="{{$transaction['trans_price']}}">
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> حدد الشركة  : </label>
                                    <select required class="form-control select2" name="company_id">
                                        <option> -- حدد الشركة  -- </option>
                                        @foreach($companies as $company)
                                            <option @if($transaction['company_id'] == $company['id']) selected @endif value="{{$company['id']}}"> {{$company['name']}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> نوع التحويل  : </label>
                                    <select required class="form-control select2" name="trans_type">
                                        <option> -- حدد نوع التحويل  -- </option>
                                        <option @if($transaction['trans_type'] =='القيد') selected @endif value="القيد">القيد</option>
                                        <option @if($transaction['trans_type'] =='التجديد') selected @endif value="التجديد">التجديد</option>
                                        <option @if($transaction['trans_type'] =='التصديق') selected @endif value="التصديق">التصديق</option>
                                        <option @if($transaction['trans_type'] =='الشهادات') selected @endif value="الشهادات">الشهادات</option>
                                    </select>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">  اضافة مرفقات   : </label>
                                    <input type="file" class="form-control" name="file">
                                    <br>
                                    <a target="-_blank" href="{{url(asset('assets/files/transaction_files/'.$transaction['file']))}}" class="btn btn-danger-gradient btn-sm"> مشاهدة المرفق  </a>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">  ملاحظات اضافية  : </label>
                                    <textarea name="notes" class="form-control" id="" cols="30" rows="5">{{$transaction['notes']}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"> تعديل المعاملة  <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </form>


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
