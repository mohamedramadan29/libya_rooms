
@extends('admin.layouts.master')
@section('title')
     اضافة معاملة مالية
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">  اضافة معاملة مالية    </span>
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

                    <form class="form-horizontal" method="post" action="{{ url('admin/transaction/store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="mb-4  btn btn-sm btn-success-gradient light-text">  اضافة معاملة مالية  </div>
                                <div class="form-group ">
                                    <label class="form-label">  رقم الايصال : </label>
                                    <input required type="text" class="form-control" name="trans_number" value="{{old('trans_number')}}">
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">  قيمة المعاملة  : </label>
                                    <input required type="number" class="form-control" name="trans_price" value="{{old('trans_price')}}">
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> حدد النشاط   : </label>
                                    <select required class="form-control select2" name="company_id">
                                        <option> -- حدد النشاط   -- </option>
                                        @foreach($companies as $company)
                                            <option @if(old('company') == $company['id']) selected @endif value="{{$company['id']}}"> {{$company['trade_name']}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> نوع التحويل  : </label>
                                     <select required class="form-control select2" name="trans_type">
                                         <option> -- حدد نوع التحويل  -- </option>
                                         <option value="قيد جديد"> قيد جديد</option>
                                         <option value="تجديد قيد"> تجديد قيد  </option>
                                         <option value="استخراج شهائد"> استخراج شهائد </option>
                                         <option value="تصديق المستندات"> تصديق المستندات  </option>
                                         <option value="ايرادات اخري">ايرادات اخرى</option>
                                     </select>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">  اضافة مرفقات   : </label>
                                    <input required type="file" class="form-control" name="file">
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">  ملاحظات اضافية  : </label>
                                    <textarea name="notes" class="form-control" id="" cols="30" rows="5">{{old('notes')}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"> اضافة معاملة <i class="fa fa-plus"></i>
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
