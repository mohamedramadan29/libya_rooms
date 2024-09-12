@extends('admin.layouts.master')
@section('title')
    اضافة شركة
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">  اضافة شركة جديدة   </span>
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

                    <form class="form-horizontal" method="post" action="{{ url('admin/companies/store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="mb-4  btn btn-sm btn-success-gradient light-text"> البيانات الاساسية</div>
                                <div class="form-group ">
                                    <label class="form-label"> اسم الممثل القانوني : </label>
                                    <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> تاريخ الميلاد : </label>
                                    <input type="date" class="form-control" name="birthdate"
                                           value="{{old('birthdate')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> مكان الميلاد : </label>
                                    <input type="text" class="form-control" name="birthplace"
                                           value="{{old('birthplace')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الجنسية : </label>
                                    <input type="text" class="form-control" name="nationality"
                                           value="{{old('nationality')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الرقم الوطني : </label>
                                    <input type="text" class="form-control" name="id_number"
                                           value="{{old('id_number')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> محل الاقامة : </label>
                                    <input type="text" class="form-control" name="place" value="{{old('place')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> رقم اثبات الشخصية : </label>
                                    <input type="text" class="form-control" name="personal_number"
                                           value="{{old('personal_number')}}">
                                </div>
                            </div>

                            <div class="col-lg-4 col-12">
                                <div class="mb-4  btn btn-sm btn-info-gradient light-text"> بيانات الشركة</div>
                                <div class="form-group ">
                                    <label class="form-label"> الاسم التجاري : </label>
                                    <input type="text" class="form-control" name="trade_name"
                                           value="{{old('trade_name')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> نوع النشاط : </label>
                                    <select class="form-control" name="category">
                                        <option value=""> -- حدد نوع النشاط -- </option>
                                        @foreach($categories as $category)
                                            <option @if(old('category') == $category['id']) selected @endif value="{{$category['id']}}"> {{$category['name']}} </option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> راس المال : </label>
                                    <input type="text" class="form-control" name="money_head"
                                           value="{{old('money_head')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> المصرف : </label>
                                    <input type="text" class="form-control" name="bank_name"
                                           value="{{old('bank_name')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> رقم الترخيص : </label>
                                    <input type="text" class="form-control" name="licenseـnumber"
                                           value="{{old('licenseـnumber')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الرقم الضريبي : </label>
                                    <input type="text" class="form-control" name="tax_number"
                                           value="{{old('tax_number')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> عنوان الشركة : </label>
                                    <input type="text" class="form-control" name="address" value="{{old('address')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الهاتف : </label>
                                    <input type="text" class="form-control" name="mobile" value="{{old('mobile')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> البريد الالكتروني : </label>
                                    <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                </div>
                            </div>

                            <div class="col-lg-4 col-12">
                                <div class="mb-4  btn btn-sm btn-primary-gradient light-text"> الشكل القانوني</div>
                                <div class="form-group ">
                                    <label class="form-label"> رقم السجل التجاري : </label>
                                    <input type="text" class="form-control" name="commercial_number"
                                           value="{{old('commercial_number')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> جهة الاصدار : </label>
                                    <input type="text" class="form-control" name="jihad_isdar"
                                           value="{{old('jihad_isdar')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> دائرة النشاط : </label>
                                    <input type="text" class="form-control" name="active_circle"
                                           value="{{old('active_circle')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> تصنيفها : </label>
                                    <select class="form-control" name="type">
                                        <option value=""> -- حدد التصنيف  -- </option>
                                        @foreach($types as $type)
                                            <option @if(old('type') == $type['id']) selected @endif value="{{$type['id']}}">{{$type['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> تاريخ صدورها : </label>
                                    <input type="date" class="form-control" name="isdar_date"
                                           value="{{old('isdar_date')}}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> حدد الفترة <span class="badge badge-info"> سنة  </span> :
                                    </label>
                                    <select class="form-control" name="isadarـduration">
                                        <option> -- حدد الفترة --</option>
                                        <option @if(old('isadarـduration') == 1) selected @endif value="1"> 1</option>
                                        <option @if(old('isadarـduration') == 2) selected @endif value="2"> 2</option>
                                        <option @if(old('isadarـduration') == 3) selected @endif value="3"> 3</option>
                                        <option @if(old('isadarـduration') == 4) selected @endif value="4"> 4</option>
                                        <option @if(old('isadarـduration') == 5) selected @endif value="5"> 5</option>
                                    </select>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> حالة الشركة </label>
                                    <select class="form-control" name="status">
                                        <option> -- حدد الحالة --</option>
                                        <option @if(old('status') == 1 ) selected @endif value="1"> فعالة</option>
                                        <option @if(old('status') == 0) selected @endif value="0"> غير فعالة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"> اضافة شركة جديدة
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