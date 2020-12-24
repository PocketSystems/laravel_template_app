@extends('layouts.app')
@section('content')

    <div class="panel">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Add Items</h4>
            </div>
            <div>
                <a href="{{route('module.items.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        @include('layouts.partials.flash_message')

        <form action="{{route('module.items.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">SKU <span class="tx-danger">*</span></label>
                    <div class="input-group mg-b-10">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="sku-field"><i class="fab fa-product-hunt"></i></span>
                        </div>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="form-control @error('sku') is-invalid @enderror" placeholder="Please enter item sku" required aria-describedby="sku-field">


                    </div>
                    @error('sku')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Categories <span class="tx-danger">*</span></label>
                    <select class="form-control select2  @error('category_id') is-invalid @enderror" name="category_id">
                        <option label="Select Category"></option>
                        @foreach ($categories as $value)
                            <option value="{{$value['id']}}">{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Item Name <span class="tx-danger">*</span></label>
                    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror" id="inputEmail4" placeholder="Please enter item name">

                    @error('name')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Item Image</label>
                    <div class="custom-file">
                        <input type="file" accept="image/*" name="image" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Cost</label>
                    <input type="number" name="cost" value="{{ old('cost') }}" class="form-control" id="inputEmail4" placeholder="Please enter item cost">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Price</label>
                    <input type="number" name="price" value="{{ old('price') }}" class="form-control" id="inputEmail4" placeholder="Please enter item price">
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Description</label>
                <textarea  class="form-control" name="description" cols="30" rows="5">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" value="1" name="saveClose" class="btn btn-warning">Save & Close</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
