<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Arazi;
use App\Models\Kisan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class KisanController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Kisan';
    }

    protected function resourceModel(): string
    {
        return Kisan::class;
    }

    protected function resourceRouteName(): string
    {
        return 'kisans';
    }

    protected function resourceColumns(): array
    {
        return ['Reg. No.', 'Arazi No.', 'Kishan Name', 'Amount', 'Location', 'Mobile'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'reg_no', 'label' => 'Reg. No.', 'type' => 'text', 'value' => $item?->reg_no],
            [
                'name' => 'legacy_arazi_no',
                'label' => 'Arazi No.',
                'type' => 'select',
                'options' => Arazi::query()
                    ->orderBy('plot_number')
                    ->get()
                    ->mapWithKeys(function (Arazi $arazi) {
                        $code = $arazi->legacy_arazi_code ?: $arazi->plot_number;
                        return [$code => $code];
                    })
                    ->all(),
                'value' => $item?->legacy_arazi_no,
            ],
            ['name' => 'name', 'label' => 'Kishan Name', 'type' => 'text', 'value' => $item?->name],
            ['name' => 'amount', 'label' => 'Amount', 'type' => 'number', 'step' => '0.01', 'value' => $item?->amount],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'value' => $item?->location],
            ['name' => 'mobile', 'label' => 'Mobile', 'type' => 'text', 'value' => $item?->mobile],
            ['name' => 'address', 'label' => 'Address', 'type' => 'text', 'value' => $item?->address],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'reg_no' => ['required', 'string', 'max:40', Rule::unique('kisans', 'reg_no')->ignore($item?->id)],
            'legacy_arazi_no' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    public function create()
    {
        $modelClass = $this->resourceModel();

        return view('kisans.form', [
            'title' => 'KISHAN REGISTRATION FORM',
            'action' => route($this->resourceRouteName() . '.store'),
            'method' => 'POST',
            'fields' => $this->resourceFields(),
            'item' => new $modelClass(),
        ]);
    }

    public function edit($id)
    {
        $modelClass = $this->resourceModel();
        $item = $modelClass::findOrFail($id);

        return view('kisans.form', [
            'title' => 'EDIT KISHAN REGISTRATION',
            'action' => route($this->resourceRouteName() . '.update', $item),
            'method' => 'PUT',
            'fields' => $this->resourceFields($item),
            'item' => $item,
        ]);
    }

    protected function resourceQuery()
    {
        return Kisan::withCount('arazis')->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Kisan $item */
        return [
            'cells' => [
                $item->reg_no ?? '-',
                $item->legacy_arazi_no ?? '-',
                $item->name,
                number_format((float) ($item->amount ?? 0), 2),
                $item->location ?? '-',
                $item->mobile,
            ],
        ];
    }
}

