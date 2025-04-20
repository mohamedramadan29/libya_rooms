@extends('admin.layouts.master')
@section('title')
    تعديل بيانات الشعبة
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية / </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> تعديل
                    بيانات الشعبة </span>
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

                    <form class="form-horizontal" method="post"
                        action="{{ url('admin/companies/update/' . $company['id']) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="mb-4  btn btn-sm btn-success-gradient light-text"> البيانات الاساسية</div>
                                <div class="form-group ">
                                    <label class="form-label"> رقم القيد : </label>
                                    <input min="1" required type="number" class="form-control" name="company_number"
                                        value="{{ $company['company_number'] }}">
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> اسم الممثل القانوني : </label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $company['name'] }}">
                                </div>
                                @if (Auth::user()->type == 'supervisor')
                                    @if (Auth::user()->branches == null)
                                        <div class="form-group">
                                            <label>المنطقة</label>
                                            <select name="regions" id="regions" class="form-control">
                                                <option value="">- حدد المنطقة -</option>
                                                @foreach ($regions as $region)
                                                    <option {{ $company['region'] == $region['id'] ? 'selected' : '' }}
                                                        value="{{ $region['id'] }}">{{ $region['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>الفرع</label>
                                            <select name="branches" id="branches" class="form-control">
                                                <option value="">- حدد الفرع -</option>
                                            </select>
                                        </div>
                                    @else
                                        <input type="hidden" name="regions" value="{{ Auth::user()->regions }}">
                                        <input type="hidden" name="branches" value="{{ Auth::user()->branches }}">
                                    @endif
                                @elseif(Auth::user()->type == 'market' || Auth::user()->type == 'money')
                                    <input type="hidden" name="regions" value="{{ Auth::user()->regions }}">
                                    <input type="hidden" name="branches" value="{{ Auth::user()->branches }}">
                                @else
                                    <div class="form-group">
                                        <label>المنطقة</label>
                                        <select name="regions" id="regions" class="form-control">
                                            <option value="">- حدد المنطقة -</option>
                                            @foreach ($regions as $region)
                                                <option {{ $company['region'] == $region['id'] ? 'selected' : '' }}
                                                    value="{{ $region['id'] }}">{{ $region['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>الفرع</label>
                                        <select name="branches" id="branches" class="form-control">
                                            <option value="">- حدد الفرع -</option>
                                        </select>
                                    </div>
                                @endif
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script type="text/javascript">
                                    var selectedBranch = {{ $company['branch'] ?? 'null' }}; // الفرع الحالي للمستخدم
                                    console.log(selectedBranch);

                                    // عندما تتغير المنطقة
                                    $('#regions').on('change', function() {
                                        var region_id = $(this).val();
                                        loadBranches(region_id, null); // تحميل الفروع بناءً على المنطقة
                                    });

                                    // دالة لتحميل الفروع
                                    function loadBranches(region_id, branch_id) {
                                        if (region_id) {
                                            $.ajax({
                                                url: '/admin/companies/get-branches/' + region_id,
                                                type: 'GET',
                                                dataType: 'json',
                                                success: function(data) {
                                                    $('#branches').empty();
                                                    $('#branches').append('<option value="">- حدد الفرع -</option>');
                                                    $.each(data, function(key, value) {
                                                        var selected = (branch_id && branch_id == value.id) ? 'selected' : '';
                                                        $('#branches').append('<option value="' + value.id + '" ' + selected + '>' +
                                                            value.name + '</option>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('#branches').empty();
                                            $('#branches').append('<option value="">- حدد الفرع -</option>');
                                        }
                                    }

                                    // عند تحميل الصفحة، إذا كانت المنطقة محددة مسبقًا، استدعِ الفروع واضبط الفرع المختار
                                    $(document).ready(function() {
                                        var region_id = $('#regions').val();
                                        if (region_id) {
                                            loadBranches(region_id, selectedBranch); // تحميل الفروع وتحديد الفرع الحالي
                                        }
                                    });
                                </script>

                                <div class="form-group ">
                                    <label class="form-label"> مكان الميلاد : </label>
                                    <input type="text" class="form-control" name="birthplace"
                                        value="{{ $company['birthplace'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الجنسية : </label>
                                    <input type="text" class="form-control" name="nationality"
                                        value="{{ $company['nationality'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الرقم الوطني : </label>
                                    <input type="text" class="form-control" name="id_number"
                                        value="{{ $company['id_number'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> محل الاقامة : </label>
                                    <input type="text" class="form-control" name="place"
                                        value="{{ $company['place'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> رقم اثبات الشخصية : </label>
                                    <input type="text" class="form-control" name="personal_number"
                                        value="{{ $company['personal_number'] }}" style="text-transform: uppercase;">
                                </div>
                            </div>

                            <div class="col-lg-4 col-12">
                                <div class="mb-4  btn btn-sm btn-info-gradient light-text"> بيانات الشركة</div>
                                <div class="form-group ">
                                    <label class="form-label"> الاسم التجاري : </label>
                                    <input type="text" class="form-control" name="trade_name"
                                        value="{{ $company['trade_name'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> حدد الشعبة : </label>
                                    <select id="main_category" class="form-control" name="category">
                                        <option value=""> -- حدد الشعبة --</option>
                                        @foreach ($categories as $category)
                                            <option @if ($company['category'] == $category['id']) selected @endif
                                                value="{{ $category['id'] }}"> {{ $category['name'] }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> نوع النشاط : </label>
                                    <select id="sub_category" class="form-control" name="sub_category">
                                        <option value=""> -- حدد نوع النشاط --</option>
                                    </select>
                                </div>

                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script type="text/javascript">
                                    var selectedsubcategory = {{ $company['sub_category'] ?? 'null' }}; // الفرع الحالي للمستخدم
                                    console.log(selectedBranch);

                                    // عندما تتغير المنطقة
                                    $('#main_category').on('change', function() {
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
                                                success: function(data) {
                                                    $('#sub_category').empty();
                                                    $('#sub_category').append('<option value="">-  حدد نوع النشاط -</option>');
                                                    $.each(data, function(key, value) {
                                                        var selected = (sub_category && sub_category == value.id) ? 'selected' : '';
                                                        $('#sub_category').append('<option value="' + value.id + '" ' + selected +
                                                            '>' + value.name + '</option>');
                                                    });
                                                }
                                            });
                                        } else {
                                            $('#sub_category').empty();
                                            $('#sub_category').append('<option value="">-  حدد نوع النشاط -</option>');
                                        }
                                    }

                                    // عند تحميل الصفحة، إذا كانت المنطقة محددة مسبقًا، استدعِ الفروع واضبط الفرع المختار
                                    $(document).ready(function() {
                                        var main_category = $('#main_category').val();
                                        if (main_category) {
                                            loadSubcategories(main_category, selectedsubcategory); // تحميل الفروع وتحديد الفرع الحالي
                                        }
                                    });
                                </script>


                                <div class="form-group ">
                                    <label class="form-label"> راس المال : </label>
                                    <input type="text" class="form-control" name="money_head"
                                        value="{{ $company['money_head'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> المصرف : </label>
                                    <input type="text" class="form-control" name="bank_name"
                                        value="{{ $company['bank_name'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> رقم الترخيص : </label>
                                    <input type="text" class="form-control" name="licenseـnumber"
                                        value="{{ $company['licenseـnumber'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الرقم الضريبي : </label>
                                    <input type="text" class="form-control" name="tax_number"
                                        value="{{ $company['tax_number'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> عنوان النشاط : </label>
                                    <input type="text" class="form-control" name="address"
                                        value="{{ $company['address'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> الهاتف : </label>
                                    <input type="text" class="form-control" name="mobile"
                                        value="{{ $company['mobile'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> البريد الالكتروني : </label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ $company['email'] }}">
                                </div>
                            </div>

                            <div class="col-lg-4 col-12">
                                <div class="mb-4  btn btn-sm btn-primary-gradient light-text"> الشكل القانوني</div>
                                <div class="form-group ">
                                    <label class="form-label"> رقم السجل التجاري : </label>
                                    <input type="text" class="form-control" name="commercial_number"
                                        value="{{ $company['commercial_number'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> جهة الاصدار : </label>
                                    <input type="text" class="form-control" name="jihad_isdar"
                                        value="{{ $company['jihad_isdar'] }}">
                                </div>

                                {{--                                <div class="form-group "> --}}
                                {{--                                    <label class="form-label"> دائرة النشاط : </label> --}}
                                {{--                                    <input type="text" class="form-control" name="active_circle" --}}
                                {{--                                           value="{{$company['active_circle']}}"> --}}
                                {{--                                </div> --}}

                                <div class="form-group ">
                                    <label class="form-label"> التصنيف : </label>
                                    <select class="form-control" name="type">
                                        <option value=""> -- حدد التصنيف --</option>
                                        @foreach ($types as $type)
                                            <option @if ($company['type'] == $type['id']) selected @endif
                                                value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> تاريخ الانتهاء : </label>
                                    <input type="date" class="form-control" name="isdar_date"
                                        value="{{ $company['isdar_date'] }}">
                                </div>

                                <div class="form-group d-none">
                                    <label class="form-label"> حدد الفترة <span class="badge badge-info"> سنة </span> :
                                    </label>
                                    <select class="form-control" name="isadarـduration">
                                        <option> -- حدد الفترة --</option>
                                        <option @if ($company['isadarـduration'] == 1) selected @endif value="1"> 1
                                        </option>
                                        <option @if ($company['isadarـduration'] == 2) selected @endif value="2"> 2
                                        </option>
                                        <option @if ($company['isadarـduration'] == 3) selected @endif value="3"> 3
                                        </option>
                                        <option @if ($company['isadarـduration'] == 4) selected @endif value="4"> 4
                                        </option>
                                        <option @if ($company['isadarـduration'] == 5) selected @endif value="5"> 5
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">تاريخ انتهاء إذن السياحة : </label>
                                    <input type="date" class="form-control" name="tourism_expire_date"
                                        value="{{ $company['tourism_expire_date'] }}">
                                </div>

                                <div class="form-group ">
                                    <label class="form-label">  رخصة النشاط : </label>
                                    <input type="file" class="form-control" name="commercial_image"
                                        accept="image/*, application/pdf">
                                    @if ($company['commercial_image'] != null)
                                        <br>
                                        <a target="_blank"
                                            href="{{ asset('assets/files/company_register/' . $company['commercial_image']) }}"
                                            class="btn btn-primary btn-sm"> مشاهدة الملف </a>
                                    @endif

                                </div>

                                <div class="form-group ">
                                    <label class="form-label">  السجل التجاري : </label>
                                    <input type="file" class="form-control" name="commercial_record"
                                        accept="image/*, application/pdf">
                                    @if ($company['commercial_record'] != null)
                                        <br>
                                        <a target="_blank"
                                            href="{{ asset('assets/files/company_register/' . $company['commercial_record']) }}"
                                            class="btn btn-primary btn-sm"> مشاهدة الملف </a>
                                    @endif
                                </div>


                                <div class="form-group ">
                                    <label class="form-label">  اذن مزاولة السياحة : </label>
                                    <input type="file" class="form-control" name="tourism_image"
                                        accept="image/*, application/pdf">
                                    @if ($company['tourism_image'] != null)
                                        <br>
                                        <a target="_blank"
                                            href="{{ asset('assets/files/company_register/' . $company['tourism_image']) }}"
                                            class="btn btn-primary btn-sm"> مشاهدة الملف </a>
                                    @endif
                                </div>

                                <div class="form-group ">
                                    <label class="form-label"> شهادة الغرفة ( لمن لدية قيد سابق ) : </label>
                                    <input type="file" class="form-control" name="room_certificate"
                                        accept="image/*, application/pdf">
                                    @if ($company['room_certificate'] != null)
                                        <br>
                                        <a target="_blank"
                                            href="{{ asset('assets/files/company_register/' . $company['room_certificate']) }}"
                                            class="btn btn-primary btn-sm"> مشاهدة الملف </a>
                                    @endif
                                </div>

                                <input type="hidden" name="status" value="1">
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"> تعديل
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
