@extends('layouts.app')

@section('title', 'MFS Report')
@section('page-title', 'MFS Report')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Provider</label>
                <select name="provider" class="form-select" onchange="this.form.submit()">
                    <option value="">All Providers</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc }}" {{ $provider == $acc ? 'selected' : '' }}>{{ $acc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('mfs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
                <a href="{{ route('mfs.transactions') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-list"></i> Transactions
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-arrow-down-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($summary['total_cash_in'], 2) }}</h3>
                <p>Total Cash In</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="bi bi-arrow-up-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($summary['total_cash_out'], 2) }}</h3>
                <p>Total Cash Out</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-cash"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($summary['total_commission'], 2) }}</h3>
                <p>Total Commission</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-counter"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $summary['transaction_count'] }}</h3>
                <p>Total Transactions</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Daily Summary</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-end">Cash In</th>
                                <th class="text-end">Cash Out</th>
                                <th class="text-end">Commission</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyStats as $date => $stats)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                                <td class="text-end text-success">{{ number_format($stats['cash_in'], 2) }}</td>
                                <td class="text-end text-warning">{{ number_format($stats['cash_out'], 2) }}</td>
                                <td class="text-end text-purple">{{ number_format($stats['commission'], 2) }}</td>
                                <td class="text-end">{{ $stats['count'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No data</td>
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
            <div class="card-header">Transaction Details</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Account</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                            <tr>
                                <td>{{ $txn->created_at->format('d M H:i') }}</td>
                                <td>{{ $txn->account->provider }}</td>
                                <td>
                                    <span class="badge {{ $txn->transaction_type === 'cash_in' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $txn->transaction_type === 'cash_in' ? 'In' : 'Out' }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format($txn->amount, 2) }}</td>
                                <td class="text-end">{{ number_format($txn->commission_earned, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No transactions</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection