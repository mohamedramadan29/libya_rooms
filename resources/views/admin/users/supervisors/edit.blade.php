@extends('admin.layouts.master')
@section('title')
    تعديل المستخدم
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">   تعديل المستخدم   </span>
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

                    <form method="post" action="{{url('admin/supervisor/update/'.$user['id'])}}" autocomplete="off">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="user_id" value="{{$user['id']}}">
                                <label> الاسم </label>
                                <input required type="text" name="name" class="form-control" value="{{$user['name']}}">
                            </div>
                            @if(Auth::user()->type == 'supervisor')
                                @if(Auth::user()->branches == null)
                                    <div class="form-group">
                                        <label>المنطقة</label>
                                        <select name="regions" id="regions" class="form-control">
                                            <option value="">- حدد المنطقة -</option>
                                            @foreach($regions as $region)
                                                <option
                                                    {{$user['regions'] == $region['id'] ? 'selected':''}} value="{{ $region['id'] }}">{{ $region['name'] }}</option>
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
                                    <input type="hidden" name="regions" value="{{Auth::user()->regions}}">
                                    <input type="hidden" name="branches" value="{{Auth::user()->branches}}">
                                @endif
                            @else
                                <div class="form-group">
                                    <label>المنطقة</label>
                                    <select name="regions" id="regions" class="form-control">
                                        <option value="">- حدد المنطقة -</option>
                                        @foreach($regions as $region)
                                            <option
                                                {{$user['regions'] == $region['id'] ? 'selected':''}} value="{{ $region['id'] }}">{{ $region['name'] }}</option>
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
                                var selectedBranch = {{ $user['branches'] ?? 'null' }}; // الفرع الحالي للمستخدم
                                console.log(selectedBranch);

                                // عندما تتغير المنطقة
                                $('#regions').on('change', function () {
                                    var region_id = $(this).val();
                                    loadBranches(region_id, null); // تحميل الفروع بناءً على المنطقة
                                });

                                // دالة لتحميل الفروع
                                function loadBranches(region_id, branch_id) {
                                    if (region_id) {
                                        $.ajax({
                                            url: '/admin/get-branches/' + region_id,
                                            type: 'GET',
                                            dataType: 'json',
                                            success: function (data) {
                                                $('#branches').empty();
                                                $('#branches').append('<option value="">- حدد الفرع -</option>');
                                                $.each(data, function (key, value) {
                                                    var selected = (branch_id && branch_id == value.id) ? 'selected' : '';
                                                    $('#branches').append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                                                });
                                            }
                                        });
                                    } else {
                                        $('#branches').empty();
                                        $('#branches').append('<option value="">- حدد الفرع -</option>');
                                    }
                                }

                                // عند تحميل الصفحة، إذا كانت المنطقة محددة مسبقًا، استدعِ الفروع واضبط الفرع المختار
                                $(document).ready(function () {
                                    var region_id = $('#regions').val();
                                    if (region_id) {
                                        loadBranches(region_id, selectedBranch); // تحميل الفروع وتحديد الفرع الحالي
                                    }
                                });
                            </script>
                            <div class="form-group">
                                <label> البريد الالكتروني </label>
                                <input required type="email" name="email" class="form-control"
                                       value="{{$user['email']}}">
                            </div>
                            <div class="form-group">
                                <label> رقم الهاتف </label>
                                <input required type="text" name="phone" class="form-control"
                                       value="{{$user['phone']}}">
                            </div>
                            <div class="form-group">
                                <label> تعديل كلمه المرور </label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label> الحاله </label>
                                <select required class="form-control" name="status">
                                    <option> -- حدد حالة --</option>
                                    <option @if($user['status'] == 1) selected @endif value="1"> فعال</option>
                                    <option @if($user['status'] == 0) selected @endif value="0">غير فعال</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn ripple btn-primary" type="submit"> تعديل
                            </button>
                            <button class="btn ripple btn-secondary" data-dismiss="modal"
                                    type="button">رجوع
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
