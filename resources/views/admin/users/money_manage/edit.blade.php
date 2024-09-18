<div class="modal" id="edit_model_{{$user['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> تعديل  السائق  </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/money-manage/update')}}" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="{{$user['id']}}">
                        <label>  الاسم  </label>
                        <input required type="text" name="name" class="form-control" value="{{$user['name']}}">
                    </div>
                    @if(Auth::user()->type == 'supervisor')
                        @if(Auth::user()->branches == null)
                            <div class="form-group">
                                <label>المنطقة</label>
                                <select name="regions" id="regions_{{$user['id']}}" class="form-control">
                                    <option value="">- حدد المنطقة -</option>
                                    @foreach($regions as $region)
                                        <option
                                            {{$user['regions'] == $region['id'] ? 'selected':''}} value="{{ $region['id'] }}">{{ $region['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>الفرع</label>
                                <select name="branches" id="branches_{{$user['id']}}" class="form-control">
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
                            <select name="regions" id="regions_{{$user['id']}}" class="form-control">
                                <option value="">- حدد المنطقة -</option>
                                @foreach($regions as $region)
                                    <option
                                        {{$user['regions'] == $region['id'] ? 'selected':''}} value="{{ $region['id'] }}">{{ $region['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الفرع</label>
                            <select name="branches" id="branches_{{$user['id']}}" class="form-control">
                                <option value="">- حدد الفرع -</option>
                            </select>
                        </div>
                    @endif

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script type="text/javascript">
                        var selectedBranch = {{ $user['branches'] ?? 'null' }}; // الفرع الحالي للمستخدم
                        console.log(selectedBranch);

                        // عندما تتغير المنطقة
                        $('#regions_{{$user['id']}}').on('change', function () {
                            var region_id = $(this).val();
                            loadBranches(region_id, null); // تحميل الفروع بناءً على المنطقة
                        });

                        // دالة لتحميل الفروع
                        function loadBranches(region_id, branch_id) {
                            if (region_id) {
                                $.ajax({
                                    url: 'get-branches/' + region_id,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function (data) {
                                        $('#branches_{{$user['id']}}').empty();
                                        $('#branches_{{$user['id']}}').append('<option value="">- حدد الفرع -</option>');
                                        $.each(data, function (key, value) {
                                            var selected = (branch_id && branch_id == value.id) ? 'selected' : '';
                                            $('#branches_{{$user['id']}}').append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                                        });
                                    }
                                });
                            } else {
                                $('#branches_{{$user['id']}}').empty();
                                $('#branches_{{$user['id']}}').append('<option value="">- حدد الفرع -</option>');
                            }
                        }

                        // عند تحميل الصفحة، إذا كانت المنطقة محددة مسبقًا، استدعِ الفروع واضبط الفرع المختار
                        $(document).ready(function () {
                            var region_id = $('#regions_{{$user['id']}}').val();
                            if (region_id) {
                                loadBranches(region_id, selectedBranch); // تحميل الفروع وتحديد الفرع الحالي
                            }
                        });
                    </script>
                    <div class="form-group">
                        <label>   البريد الالكتروني   </label>
                        <input required type="email" name="email" class="form-control" value="{{$user['email']}}">
                    </div>
                    <div class="form-group">
                        <label>   رقم الهاتف   </label>
                        <input required type="text" name="phone" class="form-control" value="{{$user['phone']}}">
                    </div>
                    <div class="form-group">
                        <label>  تعديل كلمه المرور  </label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>  الحاله  </label>
                        <select required class="form-control" name="status">
                            <option> -- حدد حالة    --  </option>
                            <option @if($user['status'] == 1) selected @endif value="1"> فعال </option>
                            <option @if($user['status'] == 0) selected @endif value="0">غير فعال </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="submit">  تعديل
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
