<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;

class AnalyticsDashboard extends Component
{
    public string|null $dateFrom = null;
    public string|null $dateTo = null;
    public array $categories = [];

    public function render()
    {
        $query = Transaction::query()
            ->when(!auth()->user()->isAdmin(), fn($q) => $q->where('user_id', auth()->id()))
            ->when($this->dateFrom, fn($q) => $q->whereDate('date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('date', '<=', $this->dateTo))
            ->when($this->categories, fn($q) => $q->whereIn('category_id', $this->categories));

        return view('livewire.analytics-dashboard', [
            'barChartData' => $this->barChart($query),
            'pieChartData' => $this->pieChart($query),
            'timeSeriesData' => $this->timeSeriesChart($query),
        ]);
    }

    private function barChart($query)
    {
        return $query->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => ['label' => $r->month, 'value' => $r->total]);
    }

    private function pieChart($query)
    {
        return $query->with('category')
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get()
            ->map(fn($r) => [
                'label' => $r->category->name,
                'value' => $r->total
            ]);
    }

    private function timeSeriesChart($query)
    {
        return $query->selectRaw('date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($r) => ['date' => $r->date->format('Y-m-d'), 'value' => $r->count]);
    }
}
