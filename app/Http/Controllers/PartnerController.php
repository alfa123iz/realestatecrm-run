<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Model;

class PartnerController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Partner';
    }

    protected function resourceModel(): string
    {
        return Partner::class;
    }

    protected function resourceRouteName(): string
    {
        return 'partners';
    }

    protected function resourceColumns(): array
    {
        return ['Name', 'Mobile', 'Share %', 'Capital', 'Joined On', 'Status'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $item?->name],
            ['name' => 'mobile', 'label' => 'Mobile', 'type' => 'text', 'value' => $item?->mobile],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'value' => $item?->address],
            ['name' => 'share_percentage', 'label' => 'Share Percentage', 'type' => 'number', 'step' => '0.01', 'value' => $item?->share_percentage],
            ['name' => 'capital_amount', 'label' => 'Capital Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->capital_amount],
            ['name' => 'joined_on', 'label' => 'Joined On', 'type' => 'date', 'value' => optional($item?->joined_on)->format('Y-m-d')],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Active', 'inactive' => 'Inactive'], 'value' => $item?->status ?? 'active'],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'mobile' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'share_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'capital_amount' => ['required', 'numeric', 'min:0'],
            'joined_on' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Partner $item */
        return [
            'cells' => [
                $item->name,
                $item->mobile,
                (string) $item->share_percentage,
                number_format((float) $item->capital_amount, 2),
                optional($item->joined_on)->format('d-m-Y') ?? '-',
                ucfirst($item->status),
            ],
        ];
    }
}
