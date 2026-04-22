<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Arazi;
use App\Models\Kisan;
use Illuminate\Database\Eloquent\Model;

class AraziController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Arazi';
    }

    protected function resourceModel(): string
    {
        return Arazi::class;
    }

    protected function resourceRouteName(): string
    {
        return 'arazis';
    }

    protected function resourceColumns(): array
    {
        return ['Legacy Code', 'Kisan', 'Location', 'Plot No', 'Block', 'Type', 'Size', 'Status'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'legacy_arazi_code', 'label' => 'Legacy Arazi Code', 'type' => 'text', 'value' => $item?->legacy_arazi_code],
            [
                'name' => 'kisan_id',
                'label' => 'Kisan',
                'type' => 'select',
                'options' => Kisan::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->kisan_id,
            ],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'value' => $item?->location],
            ['name' => 'plot_number', 'label' => 'Plot Number', 'type' => 'text', 'value' => $item?->plot_number],
            ['name' => 'block', 'label' => 'Block', 'type' => 'text', 'value' => $item?->block],
            ['name' => 'plot_type', 'label' => 'Plot Type', 'type' => 'text', 'value' => $item?->plot_type],
            ['name' => 'size', 'label' => 'Size', 'type' => 'number', 'step' => '0.01', 'value' => $item?->size],
            ['name' => 'coordinates', 'label' => 'Coordinates', 'type' => 'text', 'value' => $item?->coordinates],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['available' => 'Available', 'sold' => 'Sold'], 'value' => $item?->status ?? 'available'],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'legacy_arazi_code' => ['nullable', 'string', 'max:50'],
            'kisan_id' => ['required', 'exists:kisans,id'],
            'location' => ['required', 'string', 'max:255'],
            'plot_number' => ['required', 'string', 'max:100'],
            'block' => ['nullable', 'string', 'max:20'],
            'plot_type' => ['nullable', 'string', 'max:50'],
            'size' => ['required', 'numeric', 'min:0'],
            'coordinates' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,sold'],
        ];
    }

    protected function resourceQuery()
    {
        return Arazi::with('kisan')->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Arazi $item */
        return [
            'cells' => [
                $item->legacy_arazi_code ?? '-',
                $item->kisan?->name ?? '-',
                $item->location,
                $item->plot_number,
                $item->block ?? '-',
                $item->plot_type ?? '-',
                (string) $item->size,
                ucfirst($item->status),
            ],
        ];
    }
}
