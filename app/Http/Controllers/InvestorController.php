<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Investor;
use Illuminate\Database\Eloquent\Model;

class InvestorController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Investor';
    }

    protected function resourceModel(): string
    {
        return Investor::class;
    }

    protected function resourceRouteName(): string
    {
        return 'investors';
    }

    protected function resourceColumns(): array
    {
        return ['Name', 'Mobile', 'Investment', 'Return %', 'Invested On', 'Status'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $item?->name],
            ['name' => 'mobile', 'label' => 'Mobile', 'type' => 'text', 'value' => $item?->mobile],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'value' => $item?->address],
            ['name' => 'investment_amount', 'label' => 'Investment Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->investment_amount],
            ['name' => 'return_percentage', 'label' => 'Return Percentage', 'type' => 'number', 'step' => '0.01', 'value' => $item?->return_percentage],
            ['name' => 'invested_on', 'label' => 'Invested On', 'type' => 'date', 'value' => optional($item?->invested_on)->format('Y-m-d')],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Active', 'closed' => 'Closed'], 'value' => $item?->status ?? 'active'],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'mobile' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'investment_amount' => ['required', 'numeric', 'min:0'],
            'return_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'invested_on' => ['nullable', 'date'],
            'status' => ['required', 'in:active,closed'],
        ];
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Investor $item */
        return [
            'cells' => [
                $item->name,
                $item->mobile,
                number_format((float) $item->investment_amount, 2),
                (string) $item->return_percentage,
                optional($item->invested_on)->format('d-m-Y') ?? '-',
                ucfirst($item->status),
            ],
        ];
    }
}
