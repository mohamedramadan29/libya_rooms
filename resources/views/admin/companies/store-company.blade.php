@extends('admin.layouts.master2')
@section('title')
    اضافة نشاط جديد
@endsection
@section('content')
    <div class="container-fluid">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <div class="d-flex">
                    <h4 class="content-title mb-0 my-auto">الرئيسية /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> اضافة
                        نشاط </span>
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

                        <form class="form-horizontal" method="post" action="{{ url('companies/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 col-12">
                                    <div class="mb-4  btn btn-sm btn-success-gradient light-text"> البيانات الاساسية</div>
                                    <div class="form-group ">
                                        <label class="form-label"> رقم القيد : </label>
                                        <input required min="1" required type="number" class="form-control"
                                            name="company_number" value="{{ old('company_number') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>المنطقة</label>
                                        <select required name="regions" id="regions" class="form-control">
                                            <option value="">- حدد المنطقة -</option>
                                            @foreach ($regions as $region)
                                                <option @if($region['id'] == old('regions')) selected @endif value="{{ $region['id'] }}">{{ $region['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label> المكتب </label>
                                        <select required name="branches" id="branches" class="form-control">
                                            <option value="">- حدد المكتب -</option>
                                        </select>
                                    </div>
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            // تحديد القيمة القديمة إذا كانت موجودة
                                            var oldBranch = "{{ old('branches') }}";

                                            // حدث تغيير على القائمة 'regions'
                                            $('#regions').on('change', function() {
                                                var region_id = $(this).val();
                                                if (region_id) {
                                                    $.ajax({
                                                        url: 'get-branches/' + region_id,
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        success: function(data) {
                                                            $('#branches').empty();
                                                            $('#branches').append('<option value="">- حدد المكتب -</option>');
                                                            $.each(data, function(key, value) {
                                                                var selected = (value.id == oldBranch) ? 'selected' : ''; // تحديد المكتب القديم
                                                                $('#branches').append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                                                            });
                                                        }
                                                    });
                                                } else {
                                                    $('#branches').empty();
                                                    $('#branches').append('<option value="">- حدد المكتب -</option>');
                                                }
                                            });

                                            // إذا كانت القيمة القديمة للمناطق محددة مسبقًا، تشغيل الحدث لتحديث المكاتب تلقائيًا
                                            if ("{{ old('regions') }}") {
                                                $('#regions').trigger('change');
                                            }
                                        });
                                    </script>


                                    <div class="form-group ">
                                        <label class="form-label"> اسم الممثل القانوني : </label>
                                        <input required type="text" class="form-control" name="name"
                                            value="{{ old('name') }}">
                                    </div>


                                    <div class="form-group ">
                                        <label class="form-label"> الجنسية : </label>
                                        <input required type="text" class="form-control" name="nationality"
                                            value="{{ old('nationality') }}">
                                    </div>


                                    <div class="form-group ">
                                        <label class="form-label"> رقم اثبات الهوية او جواز السفر : </label>
                                        <input required type="text" class="form-control" name="personal_number"
                                            value="{{ old('personal_number') }}" style="text-transform: uppercase;">
                                    </div>


                                    <div class="form-group ">
                                        <label class="form-label"> مكان الميلاد : </label>
                                        <input required type="text" class="form-control" name="birthplace"
                                            value="{{ old('birthplace') }}">
                                    </div>


                                    <div class="form-group ">
                                        <label class="form-label"> الرقم الوطني : </label>
                                        <input required type="text" class="form-control" name="id_number"
                                            value="{{ old('id_number') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> محل الاقامة : </label>
                                        <input required type="text" class="form-control" name="place"
                                            value="{{ old('place') }}">
                                    </div>

                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-4  btn btn-sm btn-info-gradient light-text"> بيانات النشاط </div>
                                    <div class="form-group">
                                        <label class="form-label"> الاسم التجاري : </label>
                                        <input required type="text" class="form-control" name="trade_name"
                                            value="{{ old('trade_name') }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">حدد الشعبة:</label>
                                        <select required id="main_category" class="form-control" name="category">
                                            <option value=""> -- حدد الشعبة --</option>
                                            @foreach ($categories as $category)
                                                <option @if (old('category') == $category['id']) selected @endif
                                                    value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">نوع النشاط:</label>
                                        <select required id="sub_category" class="form-control" name="sub_category">
                                            <option value=""> -- حدد نوع النشاط --</option>
                                        </select>
                                    </div>

                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            // تخزين القيمة القديمة (old value) إذا كانت موجودة
                                            var oldSubCategory = "{{ old('sub_category') }}";

                                            // حدث تغيير عند اختيار الشعبة
                                            $('#main_category').on('change', function() {
                                                var main_category = $(this).val();
                                                if (main_category) {
                                                    $.ajax({
                                                        url: 'get-subcategories/' + main_category,
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        success: function(data) {
                                                            $('#sub_category').empty();
                                                            $('#sub_category').append('<option value=""> -- حدد نوع النشاط --</option>');
                                                            $.each(data, function(key, value) {
                                                                var selected = (value.id == oldSubCategory) ? 'selected' : ''; // تحديد الخيار القديم
                                                                $('#sub_category').append('<option ' + selected + ' value="' + value.id + '">' +
                                                                    value.name + '</option>');
                                                            });
                                                        }
                                                    });
                                                } else {
                                                    $('#sub_category').empty();
                                                    $('#sub_category').append('<option value=""> -- حدد نوع النشاط --</option>');
                                                }
                                            });

                                            // إذا كانت الشعبة القديمة محددة مسبقًا، تحديث نوع النشاط تلقائيًا
                                            if ("{{ old('category') }}") {
                                                $('#main_category').trigger('change');
                                            }
                                        });
                                    </script>



                                    <div class="form-group ">
                                        <label class="form-label"> راس المال : </label>
                                        <input required type="text" class="form-control" name="money_head"
                                            value="{{ old('money_head') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> المصرف : </label>
                                        <input required type="text" class="form-control" name="bank_name"
                                            value="{{ old('bank_name') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> رقم الترخيص : </label>
                                        <input required type="text" class="form-control" name="licenseـnumber"
                                            value="{{ old('licenseـnumber') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> الرقم الضريبي : </label>
                                        <input required type="text" class="form-control" name="tax_number"
                                            value="{{ old('tax_number') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> عنوان النشاط : </label>
                                        <input required type="text" class="form-control" name="address"
                                            value="{{ old('address') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> الهاتف : </label>
                                        <input required type="text" class="form-control" name="mobile"
                                            value="{{ old('mobile') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> البريد الالكتروني : </label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email') }}">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12">
                                    <div class="mb-4  btn btn-sm btn-warning-gradient light-text"> الشكل القانوني</div>
                                    <div class="form-group ">
                                        <label class="form-label"> رقم السجل التجاري : </label>
                                        <input required type="text" class="form-control" name="commercial_number"
                                            value="{{ old('commercial_number') }}">
                                    </div>

                                    <div class="form-group ">
                                        <label class="form-label"> جهة الاصدار : </label>
                                        <input required type="text" class="form-control" name="jihad_isdar"
                                            value="{{ old('jihad_isdar') }}">
                                    </div>


                                    <div class="form-group ">
                                        <label class="form-label"> التصنيف : </label>
                                        <select required class="form-control" name="type">
                                            <option value=""> -- حدد التصنيف --</option>
                                            @foreach ($types as $type)
                                                <option @if (old('type') == $type['id']) selected @endif
                                                    value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"> تاريخ الانتهاء : </label>
                                        <input required type="date" class="form-control" name="isdar_date"
                                            value="{{ old('isdar_date') }}">
                                    </div>

                                    <div class="form-group" style="display: none">
                                        <label class="form-label"> حدد الفترة <span class="badge badge-info"> سنة </span>
                                            :
                                        </label>
                                        <select class="form-control" name="isadarـduration">
                                            <option> -- حدد الفترة --</option>
                                            <option @if (old('isadarـduration') == 1) selected @endif value="1"> 1
                                            </option>
                                            <option @if (old('isadarـduration') == 2) selected @endif value="2"> 2
                                            </option>
                                            <option @if (old('isadarـduration') == 3) selected @endif value="3"> 3
                                            </option>
                                            <option @if (old('isadarـduration') == 4) selected @endif value="4"> 4
                                            </option>
                                            <option @if (old('isadarـduration') == 5) selected @endif value="5"> 5
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group ">
                                        <label class="form-label">تاريخ انتهاء إذن السياحة : </label>
                                        <input type="date" class="form-control" name="tourism_expire_date"
                                            value="{{ old('tourism_expire_date') }}">
                                    </div>
                                    <input type="hidden" name="status" value="1">

                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-primary waves-effect waves-light"> اضافة
                                    <i class="fa fa-plus"></i>
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
    </div>
    <!-- main-content closed -->
@endsection
