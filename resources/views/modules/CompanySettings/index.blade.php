@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Edit Company Settings</h4>
            </div>

        </div>
        @include('layouts.partials.flash_message')

        <form action="{{route('module.'.$moduleName.'.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put" />
            <input type="hidden" name="id" value="{{$data['id']}}" />
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="inputEmail4">Company Name <span class="tx-danger">*</span></label>
                    <input type="text" name="name" value="{{$data['name']}}" required class="form-control @error('name') is-invalid @enderror" id="inputEmail4" placeholder="Please enter supplier name">
                    @error('name')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group {{!empty($data['logo']) ? 'col-md-3':'col-md-4'}}" id="image-field">
                    <label for="inputEmail4">Company Logo</label>
                    <div class="custom-file">
                        <input type="file" accept="image/*" name="logo" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    @error('logo')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                @if(!empty($data['logo']))
                <div class="form-group col-md-1 mt-md-3" id="image-box" >
                    <a data-fancybox="gallery" href="{{url($data['logo'] ?: '')}}"><img class="rounded " src="{{url($data['logo'] ?: '')}}" width="50" height="50"></a>&nbsp;&nbsp;
                   @php $deleteFun = "deleteFile(".$data["id"].",'".route('module.companySettings.deleteFile',[$data["id"],'logo'])."','".csrf_token()."','".$data['logo']."','logo')" @endphp
                    <a href="javascript:" onclick="{{$deleteFun}}"><i class="fas fa-trash center-form" style="color: red;" ></i></a>
                </div>

                @endif
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Phone <span class="tx-danger">*</span></label>
                    <input type="number" name="phone" value="{{$data['phone']}}" class="form-control @error('phone') is-invalid @enderror" id="inputEmail4" placeholder="Please enter phone number">
                    @error('phone')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Email</label>
                    <input type="email" name="email"  value="{{$data['email']}}" class="form-control @error('email') is-invalid @enderror" id="inputEmail4" placeholder="Please enter email address">
                    @error('email')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" name="address" value="{{$data['address']}}" class="form-control @error('address') is-invalid @enderror" id="inputAddress" placeholder="1234 Main St">
                @error('address')
                <div class="tx-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
