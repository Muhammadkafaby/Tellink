@extends('layouts.app')

@section('title', 'Welcome to Tellink')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-center">
                <h4>Welcome to Tellink</h4>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('asset/logo.png') }}" alt="Tellink Logo" class="mb-4" style="max-height: 150px;">
                
                <h5 class="mb-3">Your Campus Communication Platform</h5>
                <p class="text-muted mb-4">
                    Connect with your fellow students, share updates, and stay informed about campus activities.
                </p>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Connect</h6>
                                <p class="card-text small">Build relationships with classmates and faculty</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Share</h6>
                                <p class="card-text small">Post updates and share your experiences</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Discover</h6>
                                <p class="card-text small">Find new opportunities and campus events</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @guest
                    <div class="mt-4">
                        <a href="{{ url('/login') }}" class="btn btn-primary btn-lg me-3">Login</a>
                        <a href="{{ url('/register') }}" class="btn btn-outline-primary btn-lg">Register</a>
                    </div>
                @else
                    <div class="mt-4">
                        <a href="{{ url('/listuser') }}" class="btn btn-primary btn-lg me-3">View Users</a>
                        <a href="{{ url('/userpost') }}" class="btn btn-outline-primary btn-lg">View Posts</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
