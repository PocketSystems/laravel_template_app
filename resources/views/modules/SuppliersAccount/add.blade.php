@extends('layouts.app')
@section('content')

    <div class="panel">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Add Payments</h4>
            </div>

        </div>
        @include('layouts.partials.flash_message')

        <form action="{{route('module.'.$moduleName.'.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Date <span class="tx-danger">*</span></label>
                    <input type="text" name="date"
                           class="form-control datepicker @error('date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y')}}">
                    @error('date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <supplier-drop-down :suppliers="{{ json_encode($suppliers) }}" errorClass="@error('supplier_id') is-invalid @enderror" errorMsg="@error('supplier_id'){{ $message }}@enderror"></supplier-drop-down>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Mode<span class="tx-danger">*</span></label>
                    <select class="form-control @error('mode') is-invalid @enderror" name="mode">
                        <option label="Select Mode" value="0"></option>
                        @foreach ($mode as $key => $value)
                            <option value="{{$value}}" {{old('status') == $key ? 'selected' : ''}}>{{$value}}</option>
                        @endforeach
                    </select>
                    @error('mode')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <supplier-amount-field  errorClass="@error('amount') is-invalid @enderror" errorMsg="@error('amount'){{ $message }}@enderror"></supplier-amount-field>

                <div class="form-group col-md-8">
                    <label for="inputEmail4">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="form-control " id="inputEmail4" placeholder="Please enter description">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" value="1" name="saveClose" class="btn btn-warning">Save & Close</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
