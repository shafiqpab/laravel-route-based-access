@extends('layouts.app')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 align-center">Permission Page</h1>
        </div>
        <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Starter Page</li>
            </ol>
        </div> -->
    </div><!-- /.row -->
@endsection()
@section('content')
<div class="row">
          <div class="col-lg-10">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Permission Table</h5>
                <div class="card-text">
                    <form method="POST" action="{{ route('permission.store') }}">
                        @csrf
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            @foreach ($users as $user)
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $user->id }}" name="user_id[]" checked>
                                            <label class="form-check-label">
                                                {{ $user->name }} 
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        @foreach ($routes as $route)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $route['permission'] }}" name="permissions_{{$user->id}}[]" @if (in_array($route['route_name'], $user->permissions->pluck('route_name')->toArray())) checked @endif>
                                                <input type="hidden" name="route_name_{{$user->id}}_{{$route['permission']}}" value="{{$route['route_name']}}">
                                                <label class="form-check-label">
                                                    {{ $route['permission'] }} 
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <br>
                                <br>
                            @endforeach
                                <button type="submit" class="btn btn-sm btn-info">save</button>
                        </div>
                    </form>
                </div>
              </div>
            </div>

            
          </div>
          
        </div>
    
@endsection
