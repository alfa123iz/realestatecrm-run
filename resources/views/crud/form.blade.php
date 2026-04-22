@extends('layouts.app')

@section('content')
    @php($hasFiles = collect($fields)->contains(fn ($field) => ($field['type'] ?? 'text') === 'file'))

    <div class="card card-outline card-primary">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $action }}" method="POST" @if($hasFiles) enctype="multipart/form-data" @endif>
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif

                <div class="row g-3">
                    @foreach($fields as $field)
                        @php($value = old($field['name'], $field['value'] ?? data_get($item, $field['name'])))
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>

                            @if(($field['type'] ?? 'text') === 'textarea')
                                <textarea
                                    id="{{ $field['name'] }}"
                                    name="{{ $field['name'] }}"
                                    class="form-control"
                                    rows="4"
                                >{{ $value }}</textarea>
                            @elseif(($field['type'] ?? 'text') === 'select')
                                <select id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-select">
                                    <option value="">Select {{ $field['label'] }}</option>
                                    @foreach($field['options'] ?? [] as $optionValue => $optionLabel)
                                        <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                    @endforeach
                                </select>
                            @elseif(($field['type'] ?? 'text') === 'file')
                                <input
                                    id="{{ $field['name'] }}"
                                    name="{{ $field['name'] }}"
                                    type="file"
                                    class="form-control"
                                    @if(!empty($field['accept'])) accept="{{ $field['accept'] }}" @endif
                                >
                            @else
                                <input
                                    id="{{ $field['name'] }}"
                                    name="{{ $field['name'] }}"
                                    type="{{ $field['type'] ?? 'text' }}"
                                    value="{{ $value }}"
                                    class="form-control"
                                    @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
                                    @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
                                >
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
