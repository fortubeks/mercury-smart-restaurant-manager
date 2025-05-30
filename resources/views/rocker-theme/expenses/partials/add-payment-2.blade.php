<div id="modal-payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-standard-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-standard-title">Add Payment to Expense [<span id="expense_amount"></span>]</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> <!-- // END .modal-header -->
            <form action="{{url('expense-payments')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Amount') }}</label>
                            <input type="number" class="form-control" name="amount" value="0" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Mode of payment') }}</label>
                            <select name="mode_of_payment" class="form-select form-control">
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
                    <input type="hidden" id="expense_id" value="" name="expense_id">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div><!-- // END .modal-footer -->
            </form>
        </div> <!-- // END .modal-content -->
    </div> <!-- // END .modal-dialog -->
</div> <!-- // END .modal -->