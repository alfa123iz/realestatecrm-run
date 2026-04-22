<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Customer;
use App\Models\Kisan;
use App\Models\Payment;
use App\Models\Registry;
use App\Services\RegistryLifecycleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ManagesCrud;

    public function __construct(private readonly RegistryLifecycleService $registryLifecycleService)
    {
    }

    protected function resourceTitle(): string
    {
        return 'Payment';
    }

    protected function resourceModel(): string
    {
        return Payment::class;
    }

    protected function resourceRouteName(): string
    {
        return 'payments';
    }

    protected function resourceColumns(): array
    {
        return ['Registry', 'Kisan', 'Customer', 'Type', 'Amount', 'Date', 'Method'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            [
                'name' => 'registry_id',
                'label' => 'Registry',
                'type' => 'select',
                'options' => Registry::with('arazi')->latest()->get()->mapWithKeys(function (Registry $registry) {
                    return [$registry->id => ($registry->arazi?->plot_number ?? 'Registry #' . $registry->id)];
                })->all(),
                'value' => $item?->registry_id,
            ],
            [
                'name' => 'kisan_id',
                'label' => 'Kisan',
                'type' => 'select',
                'options' => Kisan::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->kisan_id,
            ],
            [
                'name' => 'customer_id',
                'label' => 'Customer',
                'type' => 'select',
                'options' => Customer::orderBy('name')->pluck('name', 'id')->all(),
                'value' => $item?->customer_id,
            ],
            ['name' => 'payment_type', 'label' => 'Payment Type', 'type' => 'select', 'options' => ['advance' => 'Advance', 'installment' => 'Installment', 'final' => 'Final', 'other' => 'Other'], 'value' => $item?->payment_type ?? 'advance'],
            ['name' => 'amount', 'label' => 'Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->amount],
            ['name' => 'payment_date', 'label' => 'Payment Date', 'type' => 'date', 'value' => optional($item?->payment_date)->format('Y-m-d')],
            ['name' => 'payment_method', 'label' => 'Payment Method', 'type' => 'text', 'value' => $item?->payment_method],
            ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea', 'value' => $item?->notes],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'registry_id' => ['nullable', 'exists:registries,id'],
            'kisan_id' => ['nullable', 'exists:kisans,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'payment_type' => ['required', 'in:advance,installment,final,other'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function resourceQuery()
    {
        return Payment::with(['registry.arazi', 'kisan', 'customer'])->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Payment $item */
        return [
            'cells' => [
                $item->registry?->arazi?->plot_number ?? '-',
                $item->kisan?->name ?? '-',
                $item->customer?->name ?? '-',
                ucfirst($item->payment_type),
                (string) $item->amount,
                optional($item->payment_date)->format('d-m-Y') ?? '-',
                $item->payment_method ?? '-',
            ],
        ];
    }

    protected function resourceAfterSave(Model $item, Request $request, array $validated, ?Model $original = null): void
    {
        /** @var Payment $item */
        if (! $item->registry) {
            return;
        }

        if ($item->payment_type === 'final') {
            $this->registryLifecycleService->markRegistryPaid($item->registry);

            return;
        }

        if ($item->payment_type === 'advance') {
            $this->registryLifecycleService->markRegistryPending($item->registry);
        }
    }
}
