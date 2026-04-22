<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Arazi;
use App\Models\Plot;
use Illuminate\Database\Eloquent\Model;

class PlotController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Plot';
    }

    protected function resourceModel(): string
    {
        return Plot::class;
    }

    protected function resourceRouteName(): string
    {
        return 'plots';
    }

    protected function resourceColumns(): array
    {
        return ['Arazi', 'Title', 'Coordinates', 'Latitude', 'Longitude'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            [
                'name' => 'arazi_id',
                'label' => 'Arazi',
                'type' => 'select',
                'options' => Arazi::orderBy('plot_number')->pluck('plot_number', 'id')->all(),
                'value' => $item?->arazi_id,
            ],
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'value' => $item?->title],
            ['name' => 'coordinates', 'label' => 'Coordinates', 'type' => 'text', 'value' => $item?->coordinates],
            ['name' => 'latitude', 'label' => 'Latitude', 'type' => 'number', 'step' => '0.000001', 'value' => $item?->latitude],
            ['name' => 'longitude', 'label' => 'Longitude', 'type' => 'number', 'step' => '0.000001', 'value' => $item?->longitude],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'value' => $item?->description],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'arazi_id' => ['required', 'exists:arazis,id'],
            'title' => ['required', 'string', 'max:150'],
            'coordinates' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ];
    }

    protected function resourceQuery()
    {
        return Plot::with('arazi')->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Plot $item */
        return [
            'cells' => [
                $item->arazi?->plot_number ?? '-',
                $item->title,
                $item->coordinates ?? '-',
                $item->latitude ?? '-',
                $item->longitude ?? '-',
            ],
        ];
    }
}
