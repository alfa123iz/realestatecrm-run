<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Customer';
    }

    protected function resourceModel(): string
    {
        return Customer::class;
    }

    protected function resourceRouteName(): string
    {
        return 'customers';
    }

    protected function resourceColumns(): array
    {
        return ['Legacy Code', 'Name', 'Mobile', 'Secondary Mobile', 'ID Doc', 'Registries'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'legacy_customer_code', 'label' => 'Legacy Customer Code', 'type' => 'text', 'value' => $item?->legacy_customer_code],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $item?->name],
            ['name' => 'mobile', 'label' => 'Mobile', 'type' => 'text', 'value' => $item?->mobile],
            ['name' => 'secondary_mobile', 'label' => 'Secondary Mobile', 'type' => 'text', 'value' => $item?->secondary_mobile],
            ['name' => 'id_document_no', 'label' => 'ID Document No', 'type' => 'text', 'value' => $item?->id_document_no],
            ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'value' => $item?->address],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'legacy_customer_code' => ['nullable', 'string', 'max:40', 'unique:customers,legacy_customer_code' . ($item ? ',' . $item->id : '')],
            'name' => ['required', 'string', 'max:150'],
            'mobile' => ['required', 'string', 'max:20'],
            'secondary_mobile' => ['nullable', 'string', 'max:20'],
            'id_document_no' => ['nullable', 'string', 'max:60'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    protected function resourceQuery()
    {
        return Customer::withCount('registries')->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Customer $item */
        return [
            'cells' => [
                $item->legacy_customer_code ?? '-',
                $item->name,
                $item->mobile,
                $item->secondary_mobile ?? '-',
                $item->id_document_no ?? '-',
                (string) $item->registries_count,
            ],
        ];
    }
}
