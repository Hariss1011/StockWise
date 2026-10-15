<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'module' => [
                'nullable',
                'in:Produk,Kategori,Supplier,Stok Masuk,Stok Keluar,Penyesuaian Stok,Pengguna',
            ],
            'action' => ['nullable', 'in:created,updated,deleted'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $logs = ActivityLog::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('user_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('module'), function ($query) use ($request) {
                $query->where('module', $request->module);
            })
            ->when($request->filled('action'), function ($query) use ($request) {
                $query->where('action', $request->action);
            })
            ->when($request->filled('from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalActivities = ActivityLog::count();

        $todayActivities = ActivityLog::whereDate(
            'created_at',
            today()
        )->count();

        $activeActorsToday = ActivityLog::whereDate(
            'created_at',
            today()
        )->distinct('user_id')->count('user_id');

        $users = User::orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('activity-logs.index', compact(
            'logs',
            'totalActivities',
            'todayActivities',
            'activeActorsToday',
            'users'
        ));
    }
}
