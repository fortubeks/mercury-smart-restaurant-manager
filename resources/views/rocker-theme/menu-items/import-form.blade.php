@extends('rocker-theme.layouts.app')
@section('content')

<div class="row mb-5">
    <div class="col-xl-6 mx-auto">

        <!--include flash message manually if you wish -->
        <form action="{{ route('menu-items.import.new') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="card-header mb-4">
                            <h4>Import New Items</h4>
                        </div>
                        <div class="card-body">
                            <h6>Step 1</h6>
                            <p class="form-check-label text-dark"><a href="{{route('menu-items.import.download-sample')}}">Download sample file</a></p>
                            <h6>Step 2</h6>
                            <label for="file" class="form-label">Import File</label>
                            <input type="file" name="file">
                        </div>
                        <div class="card-footer">
                            <h6>Step 3</h6>
                            <button class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-xl-6 mx-auto">

        <!--include flash message manually if you wish -->
        <form action="{{ route('menu-items.import.existing') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="card-header mb-4">
                            <h4>Update Existing Items</h4>
                        </div>
                        <div class="card-body">
                            <h6>Step 1</h6>
                            <p class="form-check-label text-dark"><a href="{{route('menu-items.download-existing')}}">Download existing items</a></p>
                            <h6>Step 2</h6>
                            <label for="file" class="form-label">Import Updated File</label>
                            <input type="file" name="file">
                        </div>
                        <div class="card-footer">
                            <h6>Step 3</h6>
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>
@endsection