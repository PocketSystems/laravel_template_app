@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Add Company</h4>
            </div>
            <div>
                <a href="{{route('module.'.$moduleName.'.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        @include('layouts.partials.flash_message')

        <form action="{{route('module.'.$moduleName.'.create')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="inputEmail4">Company Name <span class="tx-danger">*</span></label>
                    <input type="text" name="name" required class="form-control @error('name') is-invalid @enderror"
                           id="inputEmail4" placeholder="Please enter company name">

                    @error('name')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="inputEmail4">Company Image</label>
                    <div class="custom-file">
                        <input type="file" accept="image/*" name="logo" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Phone</label>
                    <input type="number" name="phone" value="{{ old('phone') }}" class="form-control" id="inputEmail4"
                           placeholder="Please enter phone number">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="inputEmail4"
                           placeholder="Please enter email address">
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="form-control" id="inputAddress"
                       placeholder="1234 Main St">
            </div>
            <div class="form-group">
                <label for="inputAddress">NTN</label>
                <input type="text" name="ntn" value="{{old('ntn')}}" class="form-control @error('ntn') is-invalid @enderror" id="inputAddress" placeholder="198786-1">

            </div>

            <div class="form-group">
                <label for="inputAddress">Note</label>
                <textarea name="note" class="form-control" cols="30" rows="10">{{old('note')}}</textarea>

            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Start Date</label>

                    <input type="text" name="start_date"
                           class="form-control datepicker @error('start_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y')}}"
                           autocomplete="off">
                    @error('start_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">End Date</label>
                    <input type="text" name="end_date"
                           class="form-control datepicker @error('end_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y',strtotime('+1 months'))}}"
                           autocomplete="off">
                    @error('end_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <button type="submit" value="1" name="saveClose" class="btn btn-warning">Save & Close</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
