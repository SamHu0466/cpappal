@extends('layouts.content')

@section('js')
@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Create Currency</h1>
        <p class="mb-4"></p>

        <form class="user" method="POST" action="{{route('currency.create')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="name" id="name" placeholder="Enter Currency Name" required>
                    <label for="name" class="input-label">Currency Name</label>
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="en_name" id="en_name" placeholder="Enter Currency English Name" required>
                    <label for="en_name" class="input-label">Currency English Name</label>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="input-material form-control" id="ratio" name="ratio" placeholder="Enter Ratio" required>
                    <label for="ratio" class="input-label">Ratio</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                Create
            </button>
        </form>
    </div>
@endsection
