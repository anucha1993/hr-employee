<?php

namespace App\Livewire\Globalsets;

use App\Models\globalsets\GlobalSetModel;
use Livewire\Component;

class GlobalSetManager extends Component
{
    public $globalSets;

    public $name;
    public $description;
    public $values = [];

    public $showModal = false;
    public $editingId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'values.*.value' => 'required|string|max:255',
        'values.*.status' => 'required|in:Enable,Disable',
    ];

    public function mount()
    {
        $this->loadSets();
    }

    public function loadSets()
    {
        $this->globalSets = GlobalSetModel::withCount('values')->latest()->get();
    }

    public function create()
    {
        $this->reset(['name', 'description', 'editingId', 'values']);
        $this->values[] = ['id' => null, 'value' => '', 'status' => 'Enable'];
        $this->showModal = true;
    }

    public function edit($id)
    {
        $set = GlobalSetModel::with('values')->findOrFail($id);
        $this->editingId = $id;
        $this->name = $set->name;
        $this->description = $set->description;
        $this->values = $set->values->map(fn($v) => [
            'id' => $v->id,
            'value' => $v->value,
            'status' => $v->status,
        ])->toArray();
        $this->showModal = true;
    }

    public function addValue()
    {
        $this->values[] = ['id' => null, 'value' => '', 'status' => 'Enable'];
    }

    public function removeValue($index)
    {
        unset($this->values[$index]);
        $this->values = array_values($this->values);
    }

    public function save()
    {
        $this->validate();

        $set = GlobalSetModel::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name, 'description' => $this->description]
        );

        // เก็บ id ของ values ที่ยังใช้งานอยู่
        $existingValueIds = [];

        foreach ($this->values as $index => $item) {
            if (!empty($item['id'])) {
                // Update existing value
                $set->values()->where('id', $item['id'])->update([
                    'value' => $item['value'],
                    'status' => $item['status'],
                    'sort_order' => $index,
                ]);
                $existingValueIds[] = $item['id'];
            } else {
                // Create new value
                $newValue = $set->values()->create([
                    'value' => $item['value'],
                    'status' => $item['status'],
                    'sort_order' => $index,
                ]);
                $existingValueIds[] = $newValue->id;
            }
        }

        // ลบเฉพาะ values ที่ถูกลบออกจาก form (ไม่อยู่ใน existingValueIds)
        $set->values()->whereNotIn('id', $existingValueIds)->delete();

        $this->loadSets();
        session()->flash('message', 'บันทึกข้อมูลสำเร็จ!');
        $this->dispatch('closeModal');
    }

    public function delete($id)
    {
        GlobalSetModel::findOrFail($id)->delete();
        $this->loadSets();
        session()->flash('message', 'ลบข้อมูลแล้ว');
    }

    public function render()
    {
      return view('livewire.globalsets.global-set-manager')->layout('layouts.vertical-main', ['title' => 'globalsets']);
    
    }
}