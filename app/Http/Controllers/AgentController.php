<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ManagesCrud;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    use ManagesCrud;

    protected function resourceTitle(): string
    {
        return 'Agent';
    }

    protected function resourceModel(): string
    {
        return Agent::class;
    }

    protected function resourceRouteName(): string
    {
        return 'agents';
    }

    protected function resourceColumns(): array
    {
        return ['Form Code', 'Name', 'Rank', 'Sponsor', 'Commission %', 'Registries'];
    }

    protected function resourceFields(?Model $item = null): array
    {
        return [
            ['name' => 'form_code', 'label' => 'Agent Form Code', 'type' => 'text', 'value' => $item?->form_code],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $item?->name],
            ['name' => 'rank_title', 'label' => 'Rank/Level', 'type' => 'text', 'value' => $item?->rank_title],
            ['name' => 'mobile', 'label' => 'Mobile', 'type' => 'text', 'value' => $item?->mobile],
            [
                'name' => 'sponsor_agent_id',
                'label' => 'Sponsor Agent',
                'type' => 'select',
                'options' => Agent::query()
                    ->when($item, fn ($q) => $q->where('id', '!=', $item->id))
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all(),
                'value' => $item?->sponsor_agent_id,
            ],
            ['name' => 'commission_percentage', 'label' => 'Commission Percentage', 'type' => 'number', 'step' => '0.01', 'value' => $item?->commission_percentage],
            ['name' => 'legacy_percent', 'label' => 'Legacy Percent', 'type' => 'number', 'step' => '0.01', 'value' => $item?->legacy_percent],
        ];
    }

    protected function resourceRules(?Model $item = null): array
    {
        return [
            'form_code' => ['nullable', 'string', 'max:30', Rule::unique('agents', 'form_code')->ignore($item?->id)],
            'name' => ['required', 'string', 'max:150'],
            'rank_title' => ['nullable', 'string', 'max:60'],
            'mobile' => ['required', 'string', 'max:20'],
            'sponsor_agent_id' => ['nullable', 'exists:agents,id'],
            'commission_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'legacy_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    protected function resourceQuery()
    {
        return Agent::with(['sponsor'])->withCount('registries')->latest();
    }

    protected function resourceRow(Model $item): array
    {
        /** @var Agent $item */
        return [
            'cells' => [
                $item->form_code ?? '-',
                $item->name,
                $item->rank_title ?? '-',
                $item->sponsor?->name ?? '-',
                (string) $item->commission_percentage,
                (string) $item->registries_count,
            ],
        ];
    }
}
