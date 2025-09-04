<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($userId)
    {
        if (!auth()->user()->can('delete user')) {
            session()->flash('error', 'คุณไม่มีสิทธิ์ลบผู้ใช้งาน');
            return;
        }

        $user = User::find($userId);
        
        if (!$user) {
            session()->flash('error', 'ไม่พบผู้ใช้งานที่ต้องการลบ');
            return;
        }

        if ($user->id === auth()->id()) {
            session()->flash('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
            return;
        }

        $user->delete();
        session()->flash('success', 'ลบผู้ใช้งานเรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.users.index', [
            'users' => User::where(function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
