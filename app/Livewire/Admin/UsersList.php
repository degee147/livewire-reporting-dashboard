<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Layout('layouts.app')]
class UsersList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->active = !$user->active;
        $user->save();
    }

    // public function impersonate($userId)
    // {
    //     session(['impersonator_id' => auth()->id()]);
    //     auth()->loginUsingId($userId);
    //     return redirect('/dashboard');
    // }

    public function impersonate($userId)
    {
        $adminId = auth()->id();

        // Prevent impersonating yourself
        if ($adminId == $userId) {
            return;
        }

        // Ensure only admin can impersonate
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Save admin ID in session for later re-login
        session()->put('impersonate', $adminId);

        // Login as the target user
        auth()->loginUsingId($userId);

        // Redirect to dashboard as impersonated user
        return redirect()->route('dashboard');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%'))
            ->paginate($this->perPage);

        return view('livewire.admin.users-list', compact('users'))
            ->layout('layouts.app', ['header' => 'Manage Users']);
    }
}
