<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Arazi;
use App\Models\Kisan;
use App\Models\KisanBond;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class KisanBondController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Kisan Bond';
    }

    protected function resourceModel(): string
    {
        return KisanBond::class;
    }

    protected function resourceRouteName(): string
    {
        return 'kisan-bonds';
    }

    protected function resourceColumns(): array
    {
        return ['Bond No', 'Kisan', 'Arazi', 'Bond Date', 'Amount'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'bond_no', 'label' => 'Bond Number', 'type' => 'text', 'value' => $item?->bond_no],
            [
                'name' => 'kisan_id',
                'label' => 'Kisan',
                'type' => 'select',
                'options' => Kisan::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->kisan_id,
            ],
            [
                'name' => 'arazi_id',
                'label' => 'Arazi',
                'type' => 'select',
                'options' => Arazi::orderBy('plot_number')->pluck('plot_number', 'id')->all(),
                'value' => $item?->arazi_id,
            ],
            ['name' => 'bond_date', 'label' => 'Bond Date', 'type' => 'date', 'value' => optional($item?->bond_date)->format('Y-m-d')],
            ['name' => 'bond_amount', 'label' => 'Bond Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->bond_amount],
            ['name' => 'witness_name', 'label' => 'Witness Name', 'type' => 'text', 'value' => $item?->witness_name],
            ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea', 'value' => $item?->notes],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'bond_no' => ['required', 'string', 'max:50', Rule::unique('kisan_bonds', 'bond_no')->ignore($item?->id)],
            'kisan_id' => ['required', 'exists:kisans,id'],
            'arazi_id' => ['required', 'exists:arazis,id'],
            'bond_date' => ['required', 'date'],
            'bond_amount' => ['required', 'numeric', 'min:0'],
            'witness_name' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function resourceQuery()
    {
        return KisanBond::with(['kisan', 'arazi'])->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var KisanBond $item */
        return [
            'cells' => [
                $item->bond_no,
                $item->kisan?->name ?? '-',
                $item->arazi?->plot_number ?? '-',
                optional($item->bond_date)->format('d-m-Y') ?? '-',
                number_format((float) $item->bond_amount, 2),
            ],
        ];
    }
}
