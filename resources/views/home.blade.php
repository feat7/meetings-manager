@extends('layouts.app')

@section('content')
        <div class="section">
          <div class="container">
            <div class="row">
              <div class="col-md-4">
                @include('dashboard.components.sidebar')
              </div>
              <div class="col-md-8"></div>
            </div>
          </div>
        </div>
@endsection
