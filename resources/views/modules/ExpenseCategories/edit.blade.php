@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Edit Expense Categories</h4>
            </div>
            <div>
                <a href="{{route('module.categories.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        <form action="{{route('module.'.$moduleName.'.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put" />
            <input type="hidden" name="id" value="{{$data['id']}}" />
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">Category Name <span class="tx-danger">*</span></label>
                    <input type="text" name="name" value="{{$data['name']}}" required class="form-control" id="inputEmail4" placeholder="Please enter category name">
                    @error('name')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
