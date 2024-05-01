@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header h3 text-center">{{ __('User Information') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row justify-content-center align-items-center g-2">
                            <div class="col-md-10 text-bold text-center">
                                <h4>Name:{{ Auth::user()->name }}</h4>
                                <h4>Role:{{ Auth::user()->roles[0]->name }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
