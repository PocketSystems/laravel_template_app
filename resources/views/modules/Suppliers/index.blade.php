@extends('layouts.app')

@section('content')



            <div class="panel">
                <div style="display: flex" class="mb-3">
                    <div style="flex: 1">
                        <h4 id="section1" class="mg-b-10">Suppliers</h4>
                    </div>
                    <div>
                        <a href="{{route('module.suppliers.add')}}" class="btn btn-primary btn-icon">
                            <i data-feather="plus"></i>
                        </a>
                    </div>
                </div>
                <table data-table="mainGrid" data-url="{{route('module.'.Request::segment(2).'.datatable')}}" data-cols='{!! base64_encode($dataTableColumns) !!}' class="table table-hover">
                    <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="30%">Name</th>
                        <th width="10%">Phone</th>
                        <th width="10%">Email</th>
                        <th width="5%">Status</th>
                        <th width="5%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>






@endsection

