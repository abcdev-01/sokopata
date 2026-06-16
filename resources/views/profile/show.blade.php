@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">My Profile</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="user_type" class="form-label">Account Type</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->user_type) }}" disabled>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-control @error('city') is-invalid @enderror" id="city" name="city">
                                <option value="Dar es Salaam" {{ $user->city == 'Dar es Salaam' ? 'selected' : '' }}>Dar es Salaam</option>
                                <option value="Arusha" {{ $user->city == 'Arusha' ? 'selected' : '' }}>Arusha</option>
                                <option value="Mwanza" {{ $user->city == 'Mwanza' ? 'selected' : '' }}>Mwanza</option>
                                <option value="Dodoma" {{ $user->city == 'Dodoma' ? 'selected' : '' }}>Dodoma</option>
                            </select>
                            @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5>Change Password (Optional)</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
            
            @if($user->business_name)
            <div class="card shadow mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Business Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Business Name:</th>
                            <td>{{ $user->business_name }}</td>
                        </tr>
                        @if($user->business_registration_number)
                        <tr>
                            <th>Registration Number:</th>
                            <td>{{ $user->business_registration_number }}</td>
                        </tr>
                        @endif
                        @if($user->national_id)
                        <tr>
                            <th>National ID/TIN:</th>
                            <td>{{ $user->national_id }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Verification Status:</th>
                            <td>
                                @if($user->is_verified)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Pending Verification</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Rating:</th>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($user->rating))
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                ({{ number_format($user->rating, 1) }})
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection