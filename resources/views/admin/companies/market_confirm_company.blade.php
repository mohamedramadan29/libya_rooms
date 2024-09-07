<div class="modal" id="market_confirm_{{$company['id']}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> هل انت متاكد من توثيق  الشركة </h6>
                <button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post"
                  action="{{url('admin/companies/market_confirm/'.$company['id'])}}">
                @csrf
                <div class="modal-body">
                    <input class="form-control" type="text" readonly
                           value="{{$company['name']}}">
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="submit">  تأكيد التوثيق
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal"
                            type="button">رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
