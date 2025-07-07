<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

#[Layout('layouts.app')]
class AnalyticsDashboard extends Component
{
    public $dateFrom, $dateTo, $categories = [], $allCategories;

    public function mount()
    {
        $this->dateFrom = now()->subMonths(12)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->allCategories = \App\Models\Category::select('id', 'name')->get();
    }

    public function render()
    {
        $data = $this->prepareChartData();
        return view('livewire.analytics-dashboard', $data)->layout('layouts.app');
    }

    protected function prepareChartData()
    {
        $cacheKey = $this->getCacheKey();

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $query = Transaction::query()
                ->with('category')
                ->when(!auth()->user()->isAdmin(), fn($q) => $q->where('user_id', Auth::id()))
                ->when($this->categories, fn($q) => $q->whereIn('category_id', $this->categories))
                ->whereBetween('date', [$this->dateFrom, $this->dateTo]);

            $transactions = $query->get();

            // Monthly Spending
            $monthly = $transactions->groupBy(fn($tx) => \Carbon\Carbon::parse($tx->date)->format('Y-m'))
                ->map(fn($group) => $group->sum('amount'));

            // Category Spending
            $category = $transactions->groupBy('category.name')
                ->map(fn($group) => $group->sum('amount'));

            // Daily Volume
            $daily = $transactions->groupBy(fn($tx) => \Carbon\Carbon::parse($tx->date)->format('Y-m-d'))
                ->map(fn($group) => count($group));

            return compact('monthly', 'category', 'daily');
        });
    }
    protected function getCacheKey(): string
    {
        $userKey = auth()->user()->isAdmin() ? 'admin' : 'user_' . auth()->id();
        $categoryKey = implode(',', $this->categories);
        return "analytics_{$userKey}_{$this->dateFrom}_{$this->dateTo}_{$categoryKey}";
    }

}
