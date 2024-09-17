<div class="modal" id="edit_model_{{$region['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">   تعديل المنطقة  </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/region/update')}}">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="region_id" value="{{$region['id']}}">

                    <div class="form-group">
                        <label>   الاسم   </label>
                        <input required type="text" name="name" class="form-control"  value="{{$region['name']}}">
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
