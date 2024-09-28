<div class="modal" id="edit_model_{{$category['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">   تعديل   </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/sub_category/edit')}}">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label> التصنيف الرئيسي  </label>
                        <input readonly disabled type="text" name="" class="form-control" value="{{$maincategory['name']}}">
                        <input type="hidden" name="parent_id" value="{{$maincategory['id']}}">
                    </div>
                    <div class="form-group">
                        <label> رقم الشعبة   </label>
                        <input type="hidden" name="cat_id" value="{{$category['id']}}">
                        <input required type="number" name="number" class="form-control" value="{{$category['number']}}">
                    </div>
                    <div class="form-group">
                        <label>   الاسم   </label>
                        <input required type="text" name="name" class="form-control"  value="{{$category['name']}}">
                    </div>
                    <div class="form-group">
                        <label>  الحالة  </label>
                        <select class="form-control" name="status">
                            <option> -- حدد الحالة -- </option>
                            <option @if($category['status'] == 1) selected @endif value="1"> فعال </option>
                            <option @if($category['status'] == 0) selected @endif value="0"> غير فعال  </option>
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
