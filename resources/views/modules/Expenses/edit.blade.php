@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Edit Expense</h4>
            </div>
            <div>
                <a href="{{route('module.'.$moduleName.'.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        <form action="{{route('module.'.$moduleName.'.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put"/>
            <input type="hidden" name="id" value="{{$data['id']}}"/>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="inputEmail4">Categories <span class="tx-danger">*</span></label>
                    <select class="form-control select2  @error('expense_category_id') is-invalid @enderror" name="expense_category_id">
                        @foreach ($categories as $value)
                            <option value="{{$value['id']}}" {{$data['expense_category_id'] == $value['id'] ? 'selected' : ''}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('expense_category_id')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>



            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Amount <span class="tx-danger">*</span></label>
                    <input type="number" name="amount" value="{{ $data['amount'] }}" class="form-control @error('amount') is-invalid   @enderror" id="inputEmail4"
                           placeholder="Please enter amount">
                    @error('amount')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Date <span class="tx-danger">*</span></label>
                    <input type="text" name="expense_date" value="{{ date('m/d/Y',strtotime($data['expense_date'])) }}"
                           class="form-control datepicker @error('expense_date') is-invalid   @enderror"
                           placeholder="Choose date">
                    @error('expense_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputAddress">Description</label>
                    <input type="text" name="description" value="{{ $data['description'] }}" class="form-control" id="description" placeholder="Enter description ...">
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
