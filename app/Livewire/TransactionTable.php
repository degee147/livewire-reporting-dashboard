<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Transaction;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class TransactionTable extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public string $search = '';
    public array $categories = [];
    public string|null $dateFrom = null;
    public string|null $dateTo = null;
    public string $sortField = 'date';
    public string $sortDirection = 'desc';

    public function updating($name)
    {
        // Reset to first page on filter change
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';

        $this->sortField = $field;
    }

    public function render()
    {
        $query = Transaction::with('category')
            ->when(!Auth::user()->isAdmin(), fn($q) => $q->where('user_id', Auth::id()))
            ->when($this->search, fn($q) => $q->where(function ($sub) {
                $sub->where('description', 'like', "%{$this->search}%")
                    ->orWhere('amount', 'like', "%{$this->search}%");
            }))
            ->when($this->categories, fn($q) => $q->whereIn('category_id', $this->categories))
            ->when($this->dateFrom, fn($q) => $q->whereDate('date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('date', '<=', $this->dateTo))
            ->orderBy($this->sortField, $this->sortDirection);

        $transactions = $query->paginate($this->perPage);

        $total = $query->sum('amount');

        return view('livewire.transaction-table', [
            'transactions' => $transactions,
            'allCategories' => Category::all(),
            'totalAmount' => $total,
        ]);
    }

}
