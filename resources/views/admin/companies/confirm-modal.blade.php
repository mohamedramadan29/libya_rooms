<div class="modal" id="company_under_view_{{$company['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> هل انت متاكد من تاكيد النشاط </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post"
                  action="{{url('admin/companies/confirm/'.$company['id'])}}">
                @csrf
                <div class="modal-body">
                    <input class="form-control" type="text" readonly
                           value="{{$company['trade_name']}}">
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-success" type="submit"> تأكيد
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
