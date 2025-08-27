<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class ShowUsers extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public function render()
    {
        return view('livewire.show-users', [
            'users' => User::orderBy('id')->paginate(10),
        ]);
    }
}
