<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersData extends Component
{
    use WithPagination;

    #[Url()]
    public $perPage = 5;

    #[Url(history:true)]
    public $search = "";

    #[Url(history:true)]
    public $userType = "";

    #[Url(history:true)]
    public $sortBy = "created_at";

    #[Url(history:true)]
    public $sortByDirection = "DESC";

    public function updatedSearch() {
        $this->resetPage();
    }

    public function delete(User $user){
        $user->delete();
    }

    public function setSortBy($sortByField) {

        if($this->sortBy === $sortByField) {
            $this->sortByDirection = ($this->sortByDirection == "ASC") ? "DESC" : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortByDirection = 'DESC';

    }

    public function render()
    {
        return view(
            'livewire.users-data',
            [
                'users' => User::search($this->search)
                    ->when($this->userType !== '', function($query){
                        $query->where('is_admin', $this->userType);
                    })
                    ->orderBy($this->sortBy, $this->sortByDirection)
                    ->paginate($this->perPage),
            ]
        );
    }
}
