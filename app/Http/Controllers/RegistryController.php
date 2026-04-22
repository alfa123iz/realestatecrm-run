<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Agent;
use App\Models\Arazi;
use App\Models\Customer;
use App\Models\Registry;
use App\Services\RegistryLifecycleService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistryController extends Controller
{
    use ManagesCrud;

    public function __construct(private readonly RegistryLifecycleService $registryLifecycleService)
    {
    }

    protected function resourceTitle(): string
    {
        return 'Registry';
    }

    protected function resourceModel(): string
    {
        return Registry::class;
    }

    protected function resourceRouteName(): string
    {
        return 'registries';
    }

    protected function resourceColumns(): array
    {
        return ['Reg Code', 'Legacy Reg No', 'Customer', 'Arazi', 'Booking Mode', 'Registry Date', 'Amount', 'Lock', 'Status'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            [
                'name' => 'customer_id',
                'label' => 'Customer',
                'type' => 'select',
                'options' => Customer::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->customer_id,
            ],
            [
                'name' => 'arazi_id',
                'label' => 'Arazi',
                'type' => 'select',
                'options' => Arazi::orderBy('plot_number')->pluck('plot_number', 'id')->all(),
                'value' => $item?->arazi_id,
            ],
            [
                'name' => 'agent_id',
                'label' => 'Agent',
                'type' => 'select',
                'options' => Agent::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->agent_id,
            ],
            [
                'name' => 'check_by_agent_id',
                'label' => 'Check By Agent',
                'type' => 'select',
                'options' => Agent::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->check_by_agent_id,
            ],
            ['name' => 'registry_code', 'label' => 'Registry Code', 'type' => 'text', 'value' => $item?->registry_code],
            ['name' => 'customer_reg_no', 'label' => 'Legacy Customer Reg No', 'type' => 'text', 'value' => $item?->customer_reg_no],
            ['name' => 'registry_date', 'label' => 'Registry Date', 'type' => 'date', 'value' => optional($item?->registry_date)->format('Y-m-d')],
            ['name' => 'booking_mode', 'label' => 'Booking Mode', 'type' => 'select', 'options' => ['cash' => 'Cash', 'emi' => 'EMI', 'mixed' => 'Mixed', 'other' => 'Other'], 'value' => $item?->booking_mode ?? 'other'],
            ['name' => 'land_size', 'label' => 'Land Size', 'type' => 'number', 'step' => '0.01', 'value' => $item?->land_size],
            ['name' => 'registry_amount', 'label' => 'Registry Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->registry_amount],
            ['name' => 'witness_name', 'label' => 'Witness Name', 'type' => 'text', 'value' => $item?->witness_name],
            ['name' => 'nominee_name', 'label' => 'Nominee Name', 'type' => 'text', 'value' => $item?->nominee_name],
            ['name' => 'broker_commission', 'label' => 'Broker Commission %', 'type' => 'number', 'step' => '0.01', 'value' => $item?->broker_commission],
            ['name' => 'advance_amount', 'label' => 'Advance Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->advance_amount],
            ['name' => 'down_payment', 'label' => 'Down Payment', 'type' => 'number', 'step' => '0.01', 'value' => $item?->down_payment],
            ['name' => 'installment_amount', 'label' => 'Installment Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->installment_amount],
            ['name' => 'due_date', 'label' => 'Due Date', 'type' => 'date', 'value' => optional($item?->due_date)->format('Y-m-d')],
            ['name' => 'expected_registry_date', 'label' => 'Expected Registry Date', 'type' => 'date', 'value' => optional($item?->expected_registry_date)->format('Y-m-d')],
            ['name' => 'payment_words', 'label' => 'Amount In Words', 'type' => 'text', 'value' => $item?->payment_words],
            ['name' => 'id_card_no', 'label' => 'ID Card No', 'type' => 'text', 'value' => $item?->id_card_no],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Pending', 'completed' => 'Completed', 'cancelled' => 'Cancelled'], 'value' => $item?->status ?? 'pending'],
            ['name' => 'payment_status', 'label' => 'Payment Status', 'type' => 'select', 'options' => ['pending' => 'Pending', 'partial' => 'Partial', 'completed' => 'Completed', 'expired' => 'Expired'], 'value' => $item?->payment_status ?? 'pending'],
            ['name' => 'lock_status', 'label' => 'Lock Status', 'type' => 'select', 'options' => ['unlock' => 'Unlock', 'lock' => 'Lock'], 'value' => $item?->lock_status ?? 'unlock'],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'arazi_id' => ['required', 'exists:arazis,id', Rule::unique('registries', 'arazi_id')->ignore($item?->id)],
            'agent_id' => ['required', 'exists:agents,id'],
            'check_by_agent_id' => ['nullable', 'exists:agents,id'],
            'registry_code' => ['nullable', 'string', 'max:40', Rule::unique('registries', 'registry_code')->ignore($item?->id)],
            'customer_reg_no' => ['nullable', 'string', 'max:40', Rule::unique('registries', 'customer_reg_no')->ignore($item?->id)],
            'registry_date' => ['required', 'date'],
            'booking_mode' => ['required', 'in:cash,emi,mixed,other'],
            'land_size' => ['required', 'numeric', 'min:0'],
            'registry_amount' => ['nullable', 'numeric', 'min:0'],
            'witness_name' => ['required', 'string', 'max:150'],
            'nominee_name' => ['nullable', 'string', 'max:150'],
            'broker_commission' => ['required', 'numeric', 'min:0', 'max:100'],
            'advance_amount' => ['nullable', 'numeric', 'min:0'],
            'down_payment' => ['nullable', 'numeric', 'min:0'],
            'installment_amount' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'expected_registry_date' => ['nullable', 'date'],
            'payment_words' => ['nullable', 'string', 'max:255'],
            'id_card_no' => ['nullable', 'string', 'max:60'],
            'status' => ['required', 'in:pending,completed,cancelled'],
            'payment_status' => ['required', 'in:pending,partial,completed,expired'],
            'lock_status' => ['required', 'in:unlock,lock'],
        ];
    }

    protected function resourceQuery()
    {
        return Registry::with(['customer', 'arazi', 'agent'])->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Registry $item */
        return [
            'cells' => [
                $item->registry_code ?? '-',
                $item->customer_reg_no ?? '-',
                $item->customer?->name ?? '-',
                $item->arazi?->plot_number ?? '-',
                strtoupper((string) $item->booking_mode),
                optional($item->registry_date)->format('d-m-Y') ?? '-',
                number_format((float) ($item->registry_amount ?? $item->land_size), 2),
                ucfirst((string) $item->lock_status),
                ucfirst($item->status),
            ],
        ];
    }

    protected function resourceAfterSave(Model $item, Request $request, array $validated, ?Model $original = null): void
    {
        /** @var Registry $item */
        if ($item->status === 'completed' || $item->payment_status === 'completed') {
            $this->registryLifecycleService->markRegistryPaid($item);
            return;
        }

        if ((float) ($item->advance_amount ?? 0) > 0 || $item->status === 'pending') {
            $item->forceFill([
                'due_date' => $item->due_date ?? Carbon::now()->addDays(15),
            ])->save();
            $this->registryLifecycleService->markRegistryPending($item);
        }
    }
}
