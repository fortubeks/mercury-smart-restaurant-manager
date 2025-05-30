<div class="modal fade" id="payment-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-standard-title">Add Payment to Bill [<span
                        id="expense_amount"></span>]</h5>
                
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> <!-- // END .modal-header -->
            <form action="{{ url('expense-payments') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>{{ __('Amount') }}</label>
                            <input type="number" class="form-control" name="amount" value="0" required>
                        </div>
                        <div class="col-md-12">
                            <label>{{ __('Mode of payment') }}</label>
                            <select name="mode_of_payment" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="pos">POS</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>{{ __('Date of payment') }}</label>
                            <input type="date" class="form-control @error('date_of_payment') is-invalid @enderror"
                                name="date_of_payment">
                        </div>
                        <div class="col-md-12">
                            <label>{{ __('Note') }}</label>
                            <input type="text" class="form-control" name="note">
                        </div>
                    </div>
                </div><!-- // END .modal-body -->
                <div class="modal-footer">
                    <input type="hidden" id="expense_id" value="" name="expense_id">
                    <input type="hidden" name="hotel_id" value="{{ auth()->user()->hotel_id }}">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div><!-- // END .modal-footer -->
            </form>
        </div> <!-- // END .modal-content -->
    </div> <!-- // END .modal-dialog -->
</div>