@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="outgoingPayments-table" class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Bank Account</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($outgoingPayments->count())
                        <tbody>
                            @foreach ($outgoingPayments as $outgoingPayment)
                            <tr>
                                <td>
                                    {{ formatDate($outgoingPayment->date_of_payment) }}
                                </td>
                                <td>{{ $outgoingPayment->payable ? class_basename($outgoingPayment->payable) . ' #' . $outgoingPayment->payable->id : '' }}</td>
                                <td>{{ $outgoingPayment->bankAccount ? $outgoingPayment->bankAccount->account_name : ''}}</td>
                                <td><span class="text-danger">{{ formatCurrency($outgoingPayment->amount) }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center order-actions">
                                        @if($outgoingPayment->payable instanceof \App\Models\ExpensePayment && $outgoingPayment->payable->expense)
                                        <a href="{{route('expenses.show',$outgoingPayment->payable->expense->id)}}" class="me-3">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        @elseif($outgoingPayment->payable instanceof \App\Models\PurchasePayment && $outgoingPayment->payable->purchase)
                                        <a href="{{route('purchases.show',$outgoingPayment->payable->purchase->id)}}" class="me-3">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        @else
                                        N/A
                                        @endif

                                        <!-- <a class="ms-3 delete-account" href="javascript:void(0);" data-account-id="{{$outgoingPayment->id}}" data-bs-toggle="modal" data-bs-target="#deleteGuestModal"><i class="bx bxs-trash"></i></a> -->
                                    </div>
                                </td>

                            </tr>

                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="7">
                                    <h5>No outgoing payments.</h5>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $outgoingPayments->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
<div class="modal fade" id="deleteGuestModal" tabindex="-1" aria-labelledby="deleteBarOrderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this outgoingPayment? Any associating outgoingPayment record will be affected
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" id="account-form" action="{{ url('bank-accounts/') }}">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>