<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait ManagesCrud
{
    abstract protected function resourceTitle(): string;

    abstract protected function resourceModel(): string;

    abstract protected function resourceRouteName(): string;

    abstract protected function resourceColumns(): array;

    abstract protected function resourceFields(?Model $item = null): array;

    abstract protected function resourceRules(?Model $item = null): array;

    protected function resourceQuery()
    {
        $modelClass = $this->resourceModel();

        return $modelClass::query()->latest();
    }

    protected function resourceRow(Model $item): array
    {
        return [];
    }

    protected function resourcePrepareData(array $validated, Request $request, ?Model $item = null): array
    {
        return $validated;
    }

    protected function resourceAfterSave(Model $item, Request $request, array $validated, ?Model $original = null): void
    {
    }

    public function index()
    {
        $records = $this->resourceQuery()->get();
        $routeName = $this->resourceRouteName();

        $rows = $records->map(function (Model $record) use ($routeName) {
            return array_merge($this->resourceRow($record), [
                'edit_url' => route($routeName . '.edit', $record),
                'delete_url' => route($routeName . '.destroy', $record),
            ]);
        })->all();

        return view('crud.index', [
            'title' => $this->resourceTitle(),
            'columns' => $this->resourceColumns(),
            'rows' => $rows,
            'createUrl' => route($routeName . '.create'),
        ]);
    }

    public function create()
    {
        $modelClass = $this->resourceModel();

        return view('crud.form', [
            'title' => 'Create ' . $this->resourceTitle(),
            'action' => route($this->resourceRouteName() . '.store'),
            'method' => 'POST',
            'fields' => $this->resourceFields(),
            'item' => new $modelClass(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->resourceRules());
        $modelClass = $this->resourceModel();
        $payload = $this->resourcePrepareData($validated, $request);
        $item = $modelClass::create($payload);
        $this->resourceAfterSave($item, $request, $validated);

        return redirect()
            ->route($this->resourceRouteName() . '.index')
            ->with('success', $this->resourceTitle() . ' created successfully.');
    }

    public function edit($id)
    {
        $modelClass = $this->resourceModel();
        $item = $modelClass::findOrFail($id);

        return view('crud.form', [
            'title' => 'Edit ' . $this->resourceTitle(),
            'action' => route($this->resourceRouteName() . '.update', $item),
            'method' => 'PUT',
            'fields' => $this->resourceFields($item),
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $modelClass = $this->resourceModel();
        $item = $modelClass::findOrFail($id);
        $validated = $request->validate($this->resourceRules($item));
        $payload = $this->resourcePrepareData($validated, $request, $item);
        $item->update($payload);
        $this->resourceAfterSave($item, $request, $validated, $item);

        return redirect()
            ->route($this->resourceRouteName() . '.index')
            ->with('success', $this->resourceTitle() . ' updated successfully.');
    }

    public function destroy($id)
    {
        $modelClass = $this->resourceModel();
        $item = $modelClass::findOrFail($id);
        $item->delete();

        return redirect()
            ->route($this->resourceRouteName() . '.index')
            ->with('success', $this->resourceTitle() . ' deleted successfully.');
    }
}
