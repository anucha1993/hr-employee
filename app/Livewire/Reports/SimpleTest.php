<?php

namespace App\Livewire\Reports;

use Livewire\Component;

class SimpleTest extends Component
{
    public function render()
    {
        return <<<'HTML'
        <div>
            <h1>Simple Test Component</h1>
            <p>If you can see this, Livewire is working correctly.</p>
        </div>
        HTML;
    }
}
