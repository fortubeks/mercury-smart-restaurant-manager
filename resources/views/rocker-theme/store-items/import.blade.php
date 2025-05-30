@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row mb-5">
            <div class="col-xl-6 mx-auto">
                <form action="{{ route('store-item.import.new') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="card-header mb-4">
                                    <h4>Import New Store Items</h4>
                                </div>
                                <div class="card-body">
                                    <h6>Step 1</h6>
                                    <p class="form-check-label text-dark"><a href="{{route('store-item.import.download-sample')}}">Download sample file</a></p>
                                    <h6>Step 2</h6>
                                    <p class="form-check-label text-dark">Update sample file with your existing store items</p>
                                    <h6>Step 3</h6>
                                    <p>
                                        <label for="file" class="form-label">Import File</label>
                                        <input type="file" name="file">
                                    </p>
                                </div>
                                <div class="card-footer">

                                    <button class="btn btn-primary">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-6 mx-auto">
                <!--include flash message manually if you wish -->
                <form action="{{ route('store-item.import.existing') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="card-header mb-4">
                                    <h4>Update Existing Store Items</h4>
                                </div>
                                <div class="card-body">
                                    <h6>Step 1</h6>
                                    <p class="form-check-label text-dark"><a href="{{route('store-item.download-existing')}}">Download existing store items</a></p>
                                    <h6>Step 2</h6>
                                    <p class="form-check-label text-dark">Update sample file with your existing store items</p>
                                    <h6>Step 3</h6>
                                    <p><label for="file" class="form-label">Import Updated File</label>
                                        <input type="file" name="file">
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>