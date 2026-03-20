@extends('layouts.app')

@section('title', 'Expenses')
@section('page-title', 'Expenses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Track all business expenses</p>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Expense
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('expenses.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search title..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            @if(request()->hasAny(['search','category','date_from','date_to']))
                <div class="col-md-1">
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Payment</th>
                        <th>Amount</th>
                        <th>Recorded by</th>
                        <th width="110">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td class="fw-semibold">{{ $expense->title }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $expense->category }}</span>
                            </td>
                            <td class="text-muted">{{ $expense->expense_date->format('d M Y') }}</td>
                            <td class="text-muted">{{ ucfirst($expense->payment_method) }}</td>
                            <td class="fw-semibold text-danger">{{ $currency }}{{ number_format($expense->amount, 2) }}</td>
                            <td class="text-muted small">{{ $expense->user->name }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-cash-stack fs-1 text-muted d-block mb-2"></i>
                                <p class="mb-0">No expenses found</p>
                                <a href="{{ route('expenses.create') }}" class="btn btn-primary mt-3">Record First Expense</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($expenses->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted small">Filtered total: <strong>{{ $currency }}{{ number_format($total, 2) }}</strong></span>
            {{ $expenses->withQueryString()->links() }}
        </div>
    @else
        <div class="card-footer text-muted small">
            Total: <strong>{{ $currency }}{{ number_format($total, 2) }}</strong>
        </div>
    @endif
</div>
@endsection
