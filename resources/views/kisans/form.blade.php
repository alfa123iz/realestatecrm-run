@extends('layouts.app')

@section('content')
    <div class="card card-outline card-success">
        <div class="card-header text-center">
            <h3 class="card-title w-100 text-danger fw-bold mb-0">{{ $title ?? 'KISHAN REGISTRATION FORM' }}</h3>
        </div>

        <div class="card-body p-0">
            @if($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $action }}" method="POST">
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle">
                        <thead>
                        <tr class="table-success">
                            <th class="text-nowrap">REG. NO.</th>
                            <th class="text-nowrap">ARAZI NO.</th>
                            <th class="text-nowrap">KISHAN NAME</th>
                            <th class="text-nowrap">AMOUNT</th>
                            <th class="text-nowrap">LOCATION</th>
                            <th class="text-nowrap">MOBILE</th>
                            <th class="text-nowrap">ADDRESS</th>
                            <th class="text-nowrap">ACTION</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            @php($regNo = old('reg_no', data_get($item, 'reg_no')))
                            @php($araziNo = old('legacy_arazi_no', data_get($item, 'legacy_arazi_no')))
                            @php($kisanName = old('name', data_get($item, 'name')))
                            @php($amount = old('amount', data_get($item, 'amount')))
                            @php($location = old('location', data_get($item, 'location')))
                            @php($mobile = old('mobile', data_get($item, 'mobile')))
                            @php($address = old('address', data_get($item, 'address')))

                            <td><input type="text" name="reg_no" value="{{ $regNo }}" class="form-control" placeholder="K0030"></td>
                            <td>
                                <select name="legacy_arazi_no" class="form-select">
                                    <option value="">0</option>
                                    @foreach(($fields[1]['options'] ?? []) as $value => $label)
                                        <option value="{{ $value }}" @selected((string) $araziNo === (string) $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="name" value="{{ $kisanName }}" class="form-control"></td>
                            <td><input type="number" step="0.01" name="amount" value="{{ $amount }}" class="form-control"></td>
                            <td><input type="text" name="location" value="{{ $location }}" class="form-control"></td>
                            <td><input type="text" name="mobile" value="{{ $mobile }}" class="form-control"></td>
                            <td><input type="text" name="address" value="{{ $address }}" class="form-control"></td>
                            <td>
                                <button type="submit" class="btn btn-primary text-nowrap">
                                    {{ $method === 'POST' ? 'ADD RECORD' : 'UPDATE RECORD' }}
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
