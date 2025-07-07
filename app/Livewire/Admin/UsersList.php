<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UsersList extends Component
{
    public function render()
    {
        return view('livewire.admin.users-list');
    }
}
