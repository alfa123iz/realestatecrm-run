<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Customer;
use App\Models\CustomerBondPayment;
use App\Models\Registry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class CustomerBondPaymentController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Customer Bond Payment';
    }

    protected function resourceModel(): string
    {
        return CustomerBondPayment::class;
    }

    protected function resourceRouteName(): string
    {
        return 'customer-bond-payments';
    }

    protected function resourceColumns(): array
    {
        return ['Entry No', 'Customer', 'Registry', 'Entry Date', 'Type', 'Amount'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'entry_no', 'label' => 'Entry Number', 'type' => 'text', 'value' => $item?->entry_no],
            [
                'name' => 'customer_id',
                'label' => 'Customer',
                'type' => 'select',
                'options' => Customer::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->customer_id,
            ],
            [
                'name' => 'registry_id',
                'label' => 'Registry',
                'type' => 'select',
                'options' => Registry::with('arazi')->latest()->get()->mapWithKeys(function (Registry $registry) {
                    $text = ($registry->registry_code ?? ('REG-' . $registry->id)) . ' / ' . ($registry->arazi?->plot_number ?? '-');
                    return [$registry->id => $text];
                })->all(),
                'value' => $item?->registry_id,
            ],
            ['name' => 'entry_date', 'label' => 'Entry Date', 'type' => 'date', 'value' => optional($item?->entry_date)->format('Y-m-d')],
            ['name' => 'entry_type', 'label' => 'Entry Type', 'type' => 'select', 'options' => ['advance' => 'Advance', 'installment' => 'Installment', 'final' => 'Final', 'penalty' => 'Penalty', 'other' => 'Other'], 'value' => $item?->entry_type ?? 'installment'],
            ['name' => 'amount', 'label' => 'Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->amount],
            ['name' => 'payment_method', 'label' => 'Payment Method', 'type' => 'text', 'value' => $item?->payment_method],
            ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'value' => $item?->remarks],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'entry_no' => ['required', 'string', 'max:50', Rule::unique('customer_bond_payments', 'entry_no')->ignore($item?->id)],
            'customer_id' => ['required', 'exists:customers,id'],
            'registry_id' => ['required', 'exists:registries,id'],
            'entry_date' => ['required', 'date'],
            'entry_type' => ['required', 'in:advance,installment,final,penalty,other'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:40'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    protected function resourceQuery()
    {
        return CustomerBondPayment::with(['customer', 'registry.arazi'])->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var CustomerBondPayment $item */
        return [
            'cells' => [
                $item->entry_no,
                $item->customer?->name ?? '-',
                ($item->registry?->registry_code ?? '-') . ' / ' . ($item->registry?->arazi?->plot_number ?? '-'),
                optional($item->entry_date)->format('d-m-Y') ?? '-',
                ucfirst($item->entry_type),
                number_format((float) $item->amount, 2),
            ],
        ];
    }
}
