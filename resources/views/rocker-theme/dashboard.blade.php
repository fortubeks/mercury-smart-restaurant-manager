@extends('rocker-theme.layouts.app')

@section('content')
@role('Manager')
@include('rocker-theme.dashboard.super-admin.home')
@endrole

@role('Waiter')

@endrole
@role('Accountant')

@endrole
@role('Store Keeper')

@endrole
@endsection