<div class="modal" id="add_model">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">  فروع المنطقة  </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/branche/store')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>  المنطقة   </label>
                        <input disabled readonly type="text" name="region_name" class="form-control" value="{{$region['name']}}">
                        <input required type="hidden" name="region_id" class="form-control" value="{{$region['id']}}">
                    </div>
                    <div class="form-group">
                        <label>   اسم الفرع   </label>
                        <input required type="text" name="name" class="form-control">
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
