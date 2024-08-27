<div class="modal" id="add_model">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> اضافة نوع جديد  </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('admin/company_type/store')}}">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label>   الاسم   </label>
                        <input required type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>  الحالة  </label>
                        <select class="form-control" name="status">
                            <option> -- حدد الحالة -- </option>
                            <option value="1"> فعال </option>
                            <option value="0"> غير فعال  </option>
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
