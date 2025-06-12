@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <!--include flash message manually if you wish -->
        <form action="{{ route('settings.app.settings.post') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">

                            <div class="row">
                                <div class="card-header mb-4">
                                    <h4>App Settings</h4>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="manage_stock" role="switch" id="flexSwitchCheckDefault1" {{ $appSetting->manage_stock == 1 ? 'checked' : ''}}>
                                        <label class="form-check-label" for="flexSwitchCheckDefault1">Manage Restaurant Inventory</label>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="include_tax" role="switch" id="flexSwitchCheckDefault3" {{ $appSetting->include_tax == 1 ? 'checked' : ''}}>
                                        <label class="form-check-label" for="flexSwitchCheckDefault3">Include Tax in Bill</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="card-header mb-4">
                                    <h4>Module Settings</h4>
                                    <p>Keep the modules you need</p>
                                </div>
                                @foreach (getModelList('modules') as $module)
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="modules[]"
                                            value="{{ $module->id }}"
                                            role="switch"
                                            id="moduleSwitch{{ $module->id }}"
                                            {{ $appSetting->restaurant->modules->contains($module->id) ? 'checked' : '' }}>
                                        <label
                                            class="form-check-label"
                                            for="moduleSwitch{{ $module->id }}">
                                            {{ $module->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>