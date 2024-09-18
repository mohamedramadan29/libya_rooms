<div class="modal" id="add_model">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">  اضافه مستخدم جديد  </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/market-manage/store')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>  الاسم  </label>
                        <input required type="text" name="name" class="form-control">
                    </div>
                    @if(Auth::user()->type == 'supervisor')
                        @if(Auth::user()->branches == null)
                            <div class="form-group">
                                <label>المنطقة</label>
                                <select name="regions" id="regions" class="form-control">
                                    <option value="">- حدد المنطقة -</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region['id'] }}">{{ $region['name'] }}</option>
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
                    @elseif(Auth::user()->type == 'market'|| Auth::user()->type == 'money')
                        <input type="hidden" name="regions" value="{{Auth::user()->regions}}">
                        <input type="hidden" name="branches" value="{{Auth::user()->branches}}">
                    @else
                        <div class="form-group">
                            <label>المنطقة</label>
                            <select name="regions" id="regions" class="form-control">
                                <option value="">- حدد المنطقة -</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region['id'] }}">{{ $region['name'] }}</option>
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
                        $('#regions').on('change', function() {
                            var region_id = $(this).val();
                            if(region_id) {
                                $.ajax({
                                    url: 'get-branches/' + region_id,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function(data) {
                                        $('#branches').empty();
                                        $('#branches').append('<option value="">- حدد الفرع -</option>');
                                        $.each(data, function(key, value) {
                                            $('#branches').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                                        });
                                    }
                                });
                            } else {
                                $('#branches').empty();
                                $('#branches').append('<option value="">- حدد الفرع -</option>');
                            }
                        });
                    </script>
                    <div class="form-group">
                        <label>   البريد الالكتروني   </label>
                        <input required type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>   رقم الهاتف   </label>
                        <input required type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>   كلمه المرور   </label>
                        <input required type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>  الحاله  </label>
                        <select required class="form-control" name="status">
                            <option> -- حدد حالة    --  </option>
                            <option value="1"> فعال </option>
                            <option value="0">غير فعال </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="submit">  إضافة
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
