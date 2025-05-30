<div id="modal-payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-standard-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-standard-title">Add Payment to Purchase [<span id="purchase_amount"></span>]</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div> <!-- // END .modal-header -->
            <form action="{{route('outgoing-payments.purchase.store')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Amount') }}</label>
                            <input type="number" class="form-control" name="amount" value="0" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Payment method') }}</label>
                            <select name="payment_method" class="form-select form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="pos">POS</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Date of payment') }}</label>
                            <input type="date" class="form-control datepicker flatpickr-input active" name="date_of_payment"
                                data-toggle="flatpickr" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="phone">Bank Account</label>
                            <select class="form-select" name="bank_account_id">
                                <option value="">--Select--</option>
                                @foreach (getModelList('bank-accounts') as $bankAccount)
                                <option value="{{$bankAccount->id}}">{{ $bankAccount->account_name. "(".formatCurrency($bankAccount->balance).")" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Note') }}</label>
                            <input type="text" class="form-control" name="note">
                        </div>
                    </div>
                </div><!-- // END .modal-body -->
                <div class="modal-footer">
                    <input type="hidden" id="purchase_id" value="" name="purchase_id">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div><!-- // END .modal-footer -->
            </form>
        </div> <!-- // END .modal-content -->
    </div> <!-- // END .modal-dialog -->
</div> <!-- // END .modal -->
<script>
    window.addEventListener('load', function() {

        $(".add-payment").click(function(event) {
            var id = $(this).data('id');
            var amount = $(this).data('amount');
            document.getElementById('purchase_id').value = id;
            document.getElementById('purchase_amount').innerHTML = amount;
        });
    });
</script>