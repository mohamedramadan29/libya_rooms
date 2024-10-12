<div class="modal" id="money_confirm_{{$company['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> هل انت متاكد من تأكيد الدفع </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data"
                  action="{{url('admin/companies/money_confirm/'.$company['id'])}}">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <input type="hidden" name="company_id" value="{{$company['id']}}">
                        <input class="form-control" type="text" readonly
                               value="{{$company['name']}}">
                    </div>

                    <div class="form-group ">
                        <label class="form-label"> رقم الايصال : </label>
                        <input required type="number" class="form-control" name="trans_number"
                               value="{{old('trans_number')}}">
                    </div>

                    <div class="form-group ">
                        <label class="form-label"> قيمة المعاملة : </label>
                        <input required type="number" class="form-control" name="trans_price"
                               value="{{old('trans_price')}}">
                    </div>

                    <div class="form-group ">
                        <label class="form-label"> نوع التحويل : </label>
                        <select required class="form-control" name="trans_type">
                            <option> -- حدد نوع التحويل --</option>
                            <option value="القيد">القيد</option>
                            <option value="التجديد">التجديد</option>
                            <option value="التصديق">التصديق</option>
                            <option value="الشهادات">الشهادات</option>
                        </select>
                    </div>
                    @if(empty($company['first_market_confirm_date']))
                        <div class="form-group ">
                            <label class="form-label"> تاريخ مخصص لشهادة القيد : </label>
                            <input type="date" class="form-control" name="special_date" value="{{old('trans_price')}}">
                        </div>
                    @endif
                    <div class="form-group ">
                        <label class="form-label"> اضافة مرفقات : </label>
                        <input type="file" class="form-control" name="file">
                    </div>
                    <div class="form-group ">
                        <label class="form-label"> ملاحظات اضافية : </label>
                        <textarea name="notes" class="form-control" id="" rows="2">{{old('notes')}}</textarea>
                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="submit"> تأكيد الدفع
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
