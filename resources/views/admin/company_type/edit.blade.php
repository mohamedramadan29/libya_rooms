<div class="modal" id="edit_model_{{$type['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">   تعديل النوع  </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/company_type/update')}}">
                @csrf

                <div class="modal-body">
                        <input type="hidden" name="type_id" value="{{$type['id']}}">

                    <div class="form-group">
                        <label>   الاسم   </label>
                        <input required type="text" name="name" class="form-control"  value="{{$type['name']}}">
                    </div>
                    <div class="form-group">
                        <label>  الحالة  </label>
                        <select class="form-control" name="status">
                            <option> -- حدد الحالة -- </option>
                            <option @if($type['status'] == 1) selected @endif value="1"> فعال </option>
                            <option @if($type['status'] == 0) selected @endif value="0"> غير فعال  </option>
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
