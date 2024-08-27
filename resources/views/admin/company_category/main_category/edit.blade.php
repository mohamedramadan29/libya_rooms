<div class="modal" id="edit_model_{{$category['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">   تعديل التصنيف   </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/main_categories/edit')}}">
                @csrf

                <div class="modal-body">
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
                        <label> نوع القسم  </label>
                        <select class="form-control" name="parent_id">
                            <option> -- حدد نوع القسم  -- </option>
                            <option value="0" @if($category['parent_id'] == 0) selected @endif> رئيسي </option>
                            @foreach($main_categories as $cat)
                                <option @if($category['parent_id'] == $cat['id']) selected @endif value="{{$cat['id']}}"> {{$cat['name']}} </option>
                            @endforeach
                        </select>
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
