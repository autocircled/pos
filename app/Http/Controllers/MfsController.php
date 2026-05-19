<?php

namespace App\Http\Controllers;

use App\Models\MfsAccount;
use App\Models\MfsTransaction;
use Illuminate\Http\Request;

class MfsController extends Controller
{
    public function index()
    {
        $accounts = MfsAccount::with(['transactions' => function ($q) {
            $q->whereDate('created_at', today());
        }])->get();

        $todayStats = [
            'cash_in' => MfsTransaction::whereDate('created_at', today())->where('transaction_type', 'cash_in')->sum('amount'),
            'cash_out' => MfsTransaction::whereDate('created_at', today())->where('transaction_type', 'cash_out')->sum('amount'),
            'commission' => MfsTransaction::whereDate('created_at', today())->sum('commission_earned'),
            'transaction_count' => MfsTransaction::whereDate('created_at', today())->count(),
        ];

        $totalStats = [
            'total_balance' => MfsAccount::sum('current_balance'),
            'total_commission' => MfsTransaction::sum('commission_earned'),
        ];

        return view('mfs.index', compact('accounts', 'todayStats', 'totalStats'));
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'provider' => 'required|string|max:50',
            'account_number' => 'required|string|max:20|unique:mfs_accounts',
            'opening_balance' => 'required|numeric|min:0',
            'cash_in_rate' => 'required|numeric|min:0|max:100',
            'cash_out_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        MfsAccount::create([
            'provider' => $request->provider,
            'account_number' => $request->account_number,
            'opening_balance' => $request->opening_balance,
            'current_balance' => $request->opening_balance,
            'cash_in_rate' => $request->cash_in_rate,
            'cash_out_rate' => $request->cash_out_rate,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'MFS Account added successfully');
    }

    public function updateAccount(Request $request, MfsAccount $account)
    {
        $request->validate([
            'provider' => 'required|string|max:50',
            'account_number' => 'required|string|max:20|unique:mfs_accounts,account_number,' . $account->id,
            'cash_in_rate' => 'required|numeric|min:0|max:100',
            'cash_out_rate' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['provider', 'account_number', 'cash_in_rate', 'cash_out_rate', 'notes']);
        $data['is_active'] = $request->has('is_active');

        $account->update($data);

        return back()->with('success', 'Account updated successfully');
    }

    public function destroyAccount(MfsAccount $account)
    {
        $account->delete();
        return back()->with('success', 'Account deleted successfully');
    }

    public function transactions(MfsAccount $account = null)
    {
        $query = MfsTransaction::with('account')->orderBy('created_at', 'desc');

        if ($account) {
            $query->where('mfs_account_id', $account->id);
        }

        $transactions = $query->paginate(20);
        $accounts = MfsAccount::where('is_active', true)->get();

        return view('mfs.transactions', compact('transactions', 'accounts', 'account'));
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'mfs_account_id' => 'required|exists:mfs_accounts,id',
            'transaction_type' => 'required|in:cash_in,cash_out',
            'amount' => 'required|numeric|min:1',
            'transaction_id' => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        MfsTransaction::create($request->all());

        $type = $request->transaction_type === 'cash_in' ? 'Cash In' : 'Cash Out';
        return back()->with('success', "{$type} transaction added successfully");
    }

    public function destroyTransaction(MfsTransaction $transaction)
    {
        $account = MfsAccount::find($transaction->mfs_account_id);
        $amount = $transaction->amount;
        $commission = $transaction->commission_earned;

        if ($transaction->transaction_type === 'cash_in') {
            $account->current_balance += $amount;
            $account->current_balance -= $commission;
        } else {
            $account->current_balance -= $amount;
            $account->current_balance -= $commission;
        }
        $account->save();

        $transaction->delete();

        return back()->with('success', 'Transaction deleted successfully');
    }

    public function report(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();
        $provider = $request->provider;

        $query = MfsTransaction::with('account')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($provider) {
            $query->whereHas('account', function ($q) use ($provider) {
                $q->where('provider', $provider);
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_cash_in' => $transactions->where('transaction_type', 'cash_in')->sum('amount'),
            'total_cash_out' => $transactions->where('transaction_type', 'cash_out')->sum('amount'),
            'total_commission' => $transactions->sum('commission_earned'),
            'transaction_count' => $transactions->count(),
        ];

        $dailyStats = $transactions->groupBy(function ($t) {
            return $t->created_at->format('Y-m-d');
        })->map(function ($day) {
            return [
                'cash_in' => $day->where('transaction_type', 'cash_in')->sum('amount'),
                'cash_out' => $day->where('transaction_type', 'cash_out')->sum('amount'),
                'commission' => $day->sum('commission_earned'),
                'count' => $day->count(),
            ];
        });

        $accounts = MfsAccount::all()->groupBy('provider')->keys();

        return view('mfs.report', compact('transactions', 'summary', 'dailyStats', 'startDate', 'endDate', 'provider', 'accounts'));
    }
}