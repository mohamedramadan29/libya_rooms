@extends('admin.layouts.master')
@section('title')
    تعديل بيانات التاجر
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                    بيانات التاجر </span>
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
                    <div class="mb-4 main-content-label">تعديل البيانات</div>

                    @if ($slug == 'personal')
                        <form class="form-horizontal" method="post" action="{{ url('admin/update_vendor/personal') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> الأسم </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name"
                                               value="{{ $vendor_person_data['name'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> العنوان </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="address"
                                               value="{{ $vendor_person_data['address'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> المدينة </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="city"
                                               value="{{ $vendor_person_data['city'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> الولاية </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="state"
                                               value="{{ $vendor_person_data['state'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> الدولة </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="country"
                                               value="{{ $vendor_person_data['country'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> الرمز البريدي </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="pincode"
                                               value="{{ $vendor_person_data['pincode'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">رقم الهاتف</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="mobile" class="form-control"
                                               value="{{ $vendor_person_data['mobile'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> البريد الألكتروني </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="email"
                                               value="{{ $vendor_person_data['email'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">الصورة </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                    </div>
                                    @if (!empty($admin_data['image']))
                                        <img width="80px" src="{{ Storage::url($admin_data['image']) }}"
                                             class="img-fluid img-thumbnail">
                                    @endif

                                </div>
                            </div>
                            <div class="card-footer text-left">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">تعديل البيانات
                                </button>
                            </div>
                        </form>
                    @elseif($slug == 'business')
                        <form class="form-horizontal" method="post" action="{{ url('admin/update_vendor/business') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <h6><strong> تحديث بيانات المتجر </strong></h6>
                            <br>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> اسم المتجر </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_name"
                                               value="@if($store_details !=null) {{ $store_details['store_name'] }} @endif ">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> رابط المتجر </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" readonly class="form-control" name="store_website"
                                               value=" @if($store_details !=null) {{ $store_details['store_website'] }} @endif">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> عنوان المتجر </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_address"
                                               value="@if($store_details !=null) {{ $store_details['store_address'] }} @endif ">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> المدينة </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_city"
                                               value="@if($store_details !=null)  {{ $store_details['store_city'] }} @endif">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> الولاية </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_state"
                                               value="@if($store_details !=null) {{ $store_details['store_state'] }} @endif ">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> الدولة </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_country"
                                               value="@if($store_details !=null) {{ $store_details['store_country'] }} @endif ">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> الرمز البريدي </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="store_pincode"
                                               value="@if($store_details !=null) {{ $store_details['store_pincode'] }} @endif ">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">رقم الهاتف</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="store_mobile" class="form-control"
                                               value=" @if($store_details !=null) {{ $store_details['store_mobile'] }} @endif ">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> البريد الألكتروني </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="store_email"
                                               value=" @if($store_details !=null) {{ $store_details['store_email'] }} @endif ">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> لوجو المتجر </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="store_logo" accept="image/*">
                                    </div>
                                    @if (!empty($store_details['store_logo']))
                                        <img width="80px" src="{{ Storage::url($store_details['store_logo']) }}"
                                             class="img-fluid img-thumbnail">
                                    @endif

                                </div>
                            </div>
                            <div class="card-footer text-left">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">تعديل البيانات
                                </button>
                            </div>
                        </form>
                    @elseif($slug == 'bank')
                        <form class="form-horizontal" method="post" action="{{ url('admin/update_vendor/bank') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <h6><strong> تحديث بيانات البنك </strong></h6>
                            <br>
                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> اسم الحساب </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="account_holder_name"
                                               value="@if($vendor_bank_details !=null) {{ $vendor_bank_details['account_holder_name'] }} @endif">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> اسم البنك </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="bank_name"
                                               value="@if($vendor_bank_details !=null) {{ $vendor_bank_details['bank_name'] }} @endif">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> رقم الحساب </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="account_number"
                                               value="@if($vendor_bank_details !=null)  {{ $vendor_bank_details['account_number'] }} @endif">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"> ifcs code </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="bank_ifsc_code"
                                               value="@if($vendor_bank_details !=null) {{ $vendor_bank_details['bank_ifsc_code'] }} @endif">
                                    </div>
                                </div>
                            </div>


                            <div class="card-footer text-left">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">تعديل البيانات
                                </button>
                            </div>
                        </form>
                    @endif


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
