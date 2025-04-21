@extends('admin.layouts.master')
@section('title')
    تعديل معاملة مالية
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الرئيسية /</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"> تعديل
                    معاملة مالية </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <!-- row -->
    <div class="row row-sm">

        <!-- Col -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if (Session::has('Success_message'))
                        <div class="alert alert-success"> {{ Session::get('Success_message') }} </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal" method="post"
                        action="{{ url('admin/transaction/update/' . $transaction['id']) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="mb-4  btn btn-sm btn-success-gradient light-text"> تعديل معاملة مالية</div>
                                <div class="form-group ">
                                    <label class="form-label"> رقم الايصال : </label>
                                    <input required type="text" class="form-control" name="trans_number"
                                        value="{{ $transaction['trans_number'] }}">
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> حدد الشركة : </label>
                                    <select required class="form-control select2" name="company_id">
                                        <option> -- حدد الشركة --</option>
                                        @foreach ($companies as $company)
                                            <option @if ($transaction['company_id'] == $company['id']) selected @endif
                                                value="{{ $company['id'] }}"> {{ $company['name'] }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="transaction-types-container">
                                    @foreach ($transactions as $trans)
                                        <div class="transaction-type-item">
                                            <div class="form-group">
                                                <label class="form-label">نوع التحويل:</label>
                                                <select required class="form-control select2" name="trans_types[]">
                                                    <option value="">-- حدد نوع التحويل --</option>
                                                    <option value="قيد جديد"
                                                        @if ($trans->trans_type == 'قيد جديد') selected @endif>
                                                        قيد جديد</option>
                                                    <option value="تجديد قيد"
                                                        @if ($trans->trans_type == 'تجديد قيد') selected @endif>
                                                        تجديد قيد</option>
                                                    <option value="استخراج شهائد"
                                                        @if ($trans->trans_type == 'استخراج شهائد') selected @endif>استخراج شهائد
                                                    </option>
                                                    <option value="تصديق المستندات"
                                                        @if ($trans->trans_type == 'تصديق المستندات') selected @endif>تصديق المستندات
                                                    </option>
                                                    <option value="ايرادات اخرى"
                                                        @if ($trans->trans_type == 'ايرادات اخرى') selected @endif>ايرادات اخرى
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">قيمة المعاملة:</label>
                                                <input required type="number" class="form-control" name="trans_prices[]"
                                                    value="{{ $trans->trans_price }}">
                                            </div>
                                            <button type="button" class="btn btn-danger remove-transaction-type"> <i
                                                    class="fa fa-trash"></i> حذف</button>
                                            <br><br>
                                            <hr>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-secondary" id="add-transaction-type">إضافة نوع
                                    آخر
                                </button>

                                {{-- <div class="form-group ">
                                    <label class="form-label"> نوع التحويل : </label>
                                    <select required class="form-control select2" name="trans_type">
                                        <option> -- حدد نوع التحويل --</option>

                                        <option @if ($transaction['trans_type'] == 'قيد جديد') selected @endif value="قيد جديد"> قيد جديد
                                        </option>
                                        <option @if ($transaction['trans_type'] == 'تجديد قيد') selected @endif value="تجديد قيد"> تجديد
                                            قيد
                                        </option>
                                        <option @if ($transaction['trans_type'] == 'استخراج شهائد') selected @endif value="استخراج شهائد">
                                            استخراج شهائد
                                        </option>
                                        <option @if ($transaction['trans_type'] == 'تصديق المستندات') selected @endif value="تصديق المستندات">
                                            تصديق المستندات
                                        </option>
                                        <option @if ($transaction['trans_type'] == 'ايرادات اخري') selected @endif value="ايرادات اخري">
                                            ايرادات اخري
                                        </option>

                                    </select>
                                </div> --}}
                                <div class="form-group ">
                                    <label class="form-label"> اضافة مرفقات : </label>
                                    <input type="file" class="form-control" name="file">
                                    <br>
                                    <a target="-_blank"
                                        href="{{ url(asset('assets/files/transaction_files/' . $transaction['file'])) }}"
                                        class="btn btn-danger-gradient btn-sm"> مشاهدة المرفق </a>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label"> ملاحظات اضافية : </label>
                                    <textarea name="notes" class="form-control" id="" cols="30" rows="5">{{ $transaction['notes'] }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"> تعديل المعاملة <i
                                    class="fa fa-plus"></i>
                            </button>
                        </div>
                    </form>


                </div>

            </div>
        </div>
        <!-- /Col -->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection


@section('js')
    <script>
        document.getElementById('add-transaction-type').addEventListener('click', function() {
            const container = document.getElementById('transaction-types-container');

            const newTransactionType = document.createElement('div');
            newTransactionType.classList.add('transaction-type-item');
            newTransactionType.innerHTML = `
    <div class="form-group">
        <label class="form-label">نوع التحويل:</label>
        <select required class="form-control select2" name="trans_types[]">
            <option value="">-- حدد نوع التحويل --</option>
            <option value="قيد جديد">قيد جديد</option>
            <option value="تجديد قيد">تجديد قيد</option>
            <option value="استخراج شهائد">استخراج شهائد</option>
            <option value="تصديق المستندات">تصديق المستندات</option>
            <option value="ايرادات اخرى">ايرادات اخرى</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">قيمة المعاملة:</label>
        <input required type="number" class="form-control" name="trans_prices[]" value="">
    </div>
    <button type="button" class="btn btn-danger remove-transaction-type"> <i class="fa fa-trash"></i> حذف</button>
<br>
<br>
<hr>
    `;
            container.appendChild(newTransactionType);

            // إضافة حدث لحذف الحقل عند الضغط على زر الحذف
            newTransactionType.querySelector('.remove-transaction-type').addEventListener('click', function() {
                newTransactionType.remove();
            });
        });
    </script>
    <script>
        $(document).on('click', '.remove-transaction-type', function() {
            $(this).closest('.transaction-type-item').remove();
        });
    </script>
@endsection
