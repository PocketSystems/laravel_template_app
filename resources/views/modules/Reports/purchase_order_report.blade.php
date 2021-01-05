@extends('layouts.app')

@section('content')


    <div class="panel">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Items</h4>
            </div>

        </div>
        <form action="{{route('module.'.$controllerName.'.search')}}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label for="inputEmail4">From Date <span class="tx-danger">*</span></label>

                    <input type="text" name="from_date"
                           class="form-control datepicker @error('from_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y')}}">
                    @error('from_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-5">
                    <label for="inputEmail4">To Date <span class="tx-danger">*</span></label>
                    <input type="text" name="to_date"
                           class="form-control datepicker @error('to_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y')}}">
                    @error('to_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-icon">
                        <i data-feather="search"></i>
                    </button>
                </div>

            </div>
        </form>
    </div>
    <br>
    <div class="panel">

        <div style="display: flex" class="mb-3">

            <div>
                <a href="" class="btn btn-primary btn-icon">
                    <i data-feather="plus"></i>
                </a>
            </div>
        </div>
        <table data-table="mainGrid" data-url="{{route('module.'.$controllerName.'.datatable')}}"
               data-cols='{!! base64_encode((!empty($dataTableColumns) ? $dataTableColumns : '')) !!}' class="table table-hover">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="5%">SKU</th>
                <th width="20%">Category</th>
                <th width="20%">Name</th>
                <th width="5%">Cost</th>
                <th width="5%">Price</th>
                <th width="5%">Status</th>
                <th width="5%">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

