@extends('layouts.app')

@section('title', 'MFS Dashboard')
@section('page-title', 'MFS Dashboard')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($totalStats['total_balance'], 2) }}</h3>
                <p>Total Balance</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-arrow-down-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($todayStats['cash_in'], 2) }}</h3>
                <p>Today's Cash In</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="bi bi-arrow-up-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($todayStats['cash_out'], 2) }}</h3>
                <p>Today's Cash Out</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-cash"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($todayStats['commission'], 2) }}</h3>
                <p>Today's Commission (4tk/1000)</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Accounts</span>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                    <i class="bi bi-plus-lg"></i> Add Account
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Account No.</th>
                                <th>Balance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $account->provider }}</span>
                                </td>
                                <td>{{ $account->account_number }}</td>
                                <td class="text-end">{{ number_format($account->current_balance, 2) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal{{ $account->id }}">
                                        <i class="bi bi-plus"></i> Transaction
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editAccountModal{{ $account->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No accounts added yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Today's Transactions</span>
                <a href="{{ route('mfs.transactions') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body p-0">
                @php
                $todayTransactions = \App\Models\MfsTransaction::with('account')->whereDate('created_at', today())->orderBy('created_at', 'desc')->limit(10)->get();
                @endphp
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Account</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayTransactions as $txn)
                            <tr>
                                <td>{{ $txn->created_at->format('H:i') }}</td>
                                <td>{{ $txn->account->provider }}</td>
                                <td>
                                    <span class="badge {{ $txn->transaction_type === 'cash_in' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $txn->transaction_type === 'cash_in' ? 'Cash In' : 'Cash Out' }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format($txn->amount, 2) }}</td>
                                <td class="text-end">{{ number_format($txn->commission_earned, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No transactions today</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>All Accounts Summary</span>
                <div>
                    <a href="{{ route('mfs.transactions') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-list"></i> Transactions
                    </a>
                    <a href="{{ route('mfs.report') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-bar-chart"></i> Report
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Account Number</th>
                                <th class="text-end">Opening Balance</th>
                                <th class="text-end">Current Balance</th>
                                <th class="text-end">Cash In Rate</th>
                                <th class="text-end">Cash Out Rate</th>
                                <th class="text-end">Today's Cash In</th>
                                <th class="text-end">Today's Cash Out</th>
                                <th class="text-end">Today's Commission</th>
                                <th class="text-end">Total Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                            <tr>
                                <td><span class="badge bg-primary">{{ $account->provider }}</span></td>
                                <td>{{ $account->account_number }}</td>
                                <td class="text-end">{{ number_format($account->opening_balance, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($account->current_balance, 2) }}</td>
                                <td class="text-end">{{ $account->cash_in_rate }}%</td>
                                <td class="text-end">{{ $account->cash_out_rate }}%</td>
                                <td class="text-end text-success">{{ number_format($account->getTodayCashIn(), 2) }}</td>
                                <td class="text-end text-warning">{{ number_format($account->getTodayCashOut(), 2) }}</td>
                                <td class="text-end text-purple">{{ number_format($account->getTodayCommission(), 2) }}</td>
                                <td class="text-end">{{ number_format($account->getTotalCommission(), 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No accounts added yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add MFS Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mfs.accounts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Provider</label>
                        <input type="text" name="provider" class="form-control" placeholder="e.g., bKash, Nagad" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opening Balance</label>
                        <input type="number" name="opening_balance" class="form-control" step="0.01" value="0" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Cash In Rate (%)</label>
                                <input type="number" name="cash_in_rate" class="form-control" step="0.001" value="0.375" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Cash Out Rate (%)</label>
                                <input type="number" name="cash_out_rate" class="form-control" step="0.001" value="0.4" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transaction Modals -->
@foreach($accounts as $account)
<div class="modal fade" id="addTransactionModal{{ $account->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Transaction - {{ $account->provider }} ({{ $account->account_number }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mfs.transactions.store') }}" method="POST">
                @csrf
                <input type="hidden" name="mfs_account_id" value="{{ $account->id }}">
                <div class="modal-body">
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
@endforeach

<!-- Edit Account Modals -->
@foreach($accounts as $account)
<div class="modal fade" id="editAccountModal{{ $account->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Account - {{ $account->provider }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mfs.accounts.update', $account) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Provider</label>
                        <input type="text" name="provider" class="form-control" value="{{ $account->provider }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control" value="{{ $account->account_number }}" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Cash In Rate (%)</label>
                                <input type="number" name="cash_in_rate" class="form-control" step="0.001" value="{{ $account->cash_in_rate }}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Cash Out Rate (%)</label>
                                <input type="number" name="cash_out_rate" class="form-control" step="0.001" value="{{ $account->cash_out_rate }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active{{ $account->id }}" value="1" {{ $account->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active{{ $account->id }}">Active</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $account->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection