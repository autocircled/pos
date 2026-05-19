@extends('layouts.app')

@section('title', 'MFS Transactions')
@section('page-title', 'MFS Transactions')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Filter by Account</label>
                <select name="account_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Accounts</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ $account && $account->id == $acc->id ? 'selected' : '' }}>
                        {{ $acc->provider }} - {{ $acc->account_number }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                    <i class="bi bi-plus-lg"></i> Add Transaction
                </button>
            </div>
            <div class="col-md-7 text-end">
                <a href="{{ route('mfs.report') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-bar-chart"></i> View Report
                </a>
                <a href="{{ route('mfs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>Provider</th>
                        <th>Account</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Commission</th>
                        <th>Customer Phone</th>
                        <th>Transaction ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr>
                        <td>{{ $txn->created_at->format('d M Y H:i') }}</td>
                        <td><span class="badge bg-primary">{{ $txn->account->provider }}</span></td>
                        <td>{{ $txn->account->account_number }}</td>
                        <td>
                            <span class="badge {{ $txn->transaction_type === 'cash_in' ? 'bg-success' : 'bg-warning' }}">
                                {{ $txn->transaction_type === 'cash_in' ? 'Cash In' : 'Cash Out' }}
                            </span>
                        </td>
                        <td class="text-end fw-bold">{{ number_format($txn->amount, 2) }}</td>
                        <td class="text-end text-purple">{{ number_format($txn->commission_earned, 2) }}</td>
                        <td>{{ $txn->customer_phone ?? '-' }}</td>
                        <td>{{ $txn->transaction_id ?? '-' }}</td>
                        <td>
                            <form action="{{ route('mfs.transactions.destroy', $txn) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this transaction? Balance will be adjusted.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No transactions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $transactions->links() }}
    </div>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mfs.transactions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Account</label>
                        <select name="mfs_account_id" class="form-select" required>
                            <option value="">Select Account</option>
                            @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->provider }} - {{ $acc->account_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction Type</label>
                        <select name="transaction_type" class="form-select" required>
                            <option value="cash_in">Cash In</option>
                            <option value="cash_out">Cash Out</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (tk)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction ID (optional)</label>
                        <input type="text" name="transaction_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer Phone (optional)</label>
                        <input type="text" name="customer_phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <small>Commission: 4tk per 1000tk will be calculated automatically</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection