@extends('layouts.app')

@section('content')
    <div class="col-12">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Barcode Auslesen</h5>
        </div>
    </div>
@endsection
