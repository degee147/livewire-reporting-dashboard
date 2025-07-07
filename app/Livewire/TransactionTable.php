<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Transaction;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class TransactionTable extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $categories = []; // Empty array means "All"
    public $perPage = 10;

    public $allCategories = [];
    public $totalAmount = 0;

    protected $queryString = ['search', 'dateFrom', 'dateTo', 'categories', 'perPage'];

    public function mount()
    {
        $this->allCategories = Category::all();
    }

    public function updated($propertyName)
    {
        // Reset pagination when filters change
        if (in_array($propertyName, ['search', 'dateFrom', 'dateTo', 'categories', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function getFilteredQuery()
    {
        $query = Transaction::query()->with('category');

        if (!auth()->user()->isAdmin()) {
            // Regular user — only show their own transactions
            $query->where('user_id', auth()->id());
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('description', 'like', "%{$this->search}%")
                    ->orWhere('amount', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('date', '<=', $this->dateTo);
        }

        // Only filter by categories if specific categories are selected
        // Empty array means "All categories"
        if (!empty($this->categories) && is_array($this->categories)) {
            $query->whereIn('category_id', $this->categories);
        }

        return $query;
    }

    // Helper method to get selected category names for display
    public function getSelectedCategoryNames()
    {
        if (empty($this->categories)) {
            return 'All Categories';
        }

        $selectedNames = $this->allCategories
            ->whereIn('id', $this->categories)
            ->pluck('name')
            ->toArray();

        if (count($selectedNames) === 1) {
            return $selectedNames[0];
        }

        return count($selectedNames) . ' categories selected';
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->categories = [];
        $this->resetPage();
    }

    public function exportCsv(): StreamedResponse
    {
        $transactions = $this->getFilteredQuery()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions.csv"',
        ];

        return response()->stream(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Category', 'Description', 'Amount', 'Status']);

            foreach ($transactions as $tx) {
                fputcsv($handle, [
                    $tx->date->format('Y-m-d'),
                    $tx->category->name ?? '-',
                    $tx->description,
                    $tx->amount,
                    $tx->status,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function render()
    {
        $query = $this->getFilteredQuery();

        // total amount of filtered transactions
        $this->totalAmount = $query->sum('amount');

        $transactions = $query
            ->latest('date')
            ->paginate($this->perPage);

        return view('livewire.transaction-table', [
            'transactions' => $transactions,
        ]);
    }
}
