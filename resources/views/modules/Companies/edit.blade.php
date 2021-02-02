@extends('layouts.app')
@section('content')

    <div class="card card-body">
        <div style="display: flex" class="mb-3">
            <div style="flex: 1">
                <h4 id="section1" class="mg-b-10">Edit Company</h4>
            </div>
            <div>
                <a href="{{route('module.'.$moduleName.'.home')}}" class="btn btn-primary btn-icon">
                    <i data-feather="arrow-left"></i>
                </a>
            </div>
        </div>
        <form action="{{route('module.'.$moduleName.'.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="put" />
            <input type="hidden" name="id" value="{{$data['id']}}" />
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="inputEmail4">Company Name <span class="tx-danger">*</span></label>
                    <input type="text" name="name" value="{{$data['name']}}" required class="form-control" id="inputEmail4" placeholder="Please enter company name">
                    @error('name')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group {{!empty($data['logo']) ? 'col-md-3':'col-md-4'}}" id="image-field">
                    <label for="inputEmail4">Company Image</label>
                    <div class="custom-file">
                        <input type="file" accept="image/*" name="logo" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                @if(!empty($data['logo']))
                <div class="form-group col-md-1 mt-md-3" id="image-box" >
                    <a data-fancybox="gallery" href="{{url($data['logo'] ?: '')}}"><img class="rounded " src="{{url($data['logo'] ?: '')}}" width="50" height="50"></a>&nbsp;&nbsp;
                   @php $deleteFun = "deleteFile(".$data["id"].",'".route('module.suppliers.deleteFile',[$data["id"],'logo'])."','".csrf_token()."','".$data['logo']."')" @endphp
                    <a href="javascript:" onclick="{{$deleteFun}}"><i class="fas fa-trash center-form" style="color: red;" ></i></a>
                </div>

                @endif
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Phone</label>
                    <input type="number" name="phone" value="{{$data['phone']}}" class="form-control" id="inputEmail4" placeholder="Please enter phone number">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Email</label>
                    <input type="email" name="email"  value="{{$data['email']}}" class="form-control" id="inputEmail4" placeholder="Please enter email address">
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" name="address" value="{{$data['address']}}" class="form-control" id="inputAddress" placeholder="1234 Main St">
            </div>
            <div class="form-group">
                <label for="inputAddress">NTN</label>
                <input type="text" name="ntn" value="{{$data['ntn']}}" class="form-control @error('ntn') is-invalid @enderror" id="inputAddress" placeholder="198786-1">

            </div>

            <div class="form-group">
                <label for="inputAddress">Note</label>
                <textarea name="note" class="form-control" cols="30" rows="10">{{$data['note']}}</textarea>

            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Start Date</label>

                    <input type="text" name="start_date"
                           class="form-control datepicker @error('start_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y',strtotime($data['start_date']))}}"
                           autocomplete="off">
                    @error('start_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail4">End Date</label>
                    <input type="text" name="end_date"
                           class="form-control datepicker @error('end_date') is-invalid @enderror"
                           placeholder="Choose date" value="{{date('m/d/Y',strtotime($data['end_date']))}}"
                           autocomplete="off">
                    @error('end_date')
                    <div class="tx-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-light">Reset</button>
        </form>
    </div>

@endsection
