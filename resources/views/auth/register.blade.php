@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Register on SokoPata</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="user_type" class="form-label">I am a</label>
                                <select class="form-control @error('user_type') is-invalid @enderror" 
                                        id="user_type" name="user_type" required>
                                    <option value="">Select Type</option>
                                    <option value="farmer">Farmer</option>
                                    <option value="cooperative">Cooperative</option>
                                    <option value="buyer">Buyer (Restaurant/Hotel/Caterer)</option>
                                </select>
                                @error('user_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        <div id="farmerFields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="business_name" class="form-label">Business/Cooperative Name</label>
                                    <input type="text" class="form-control" id="business_name" name="business_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="national_id" class="form-label">National ID / TIN</label>
                                    <input type="text" class="form-control" id="national_id" name="national_id">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-control" id="city" name="city">
                                <option value="Dar es Salaam">Dar es Salaam</option>
                                <option value="Arusha">Arusha</option>
                                <option value="Mwanza">Mwanza</option>
                                <option value="Dodoma">Dodoma</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('user_type').addEventListener('change', function() {
        var farmerFields = document.getElementById('farmerFields');
        if (this.value === 'farmer' || this.value === 'cooperative') {
            farmerFields.style.display = 'block';
        } else {
            farmerFields.style.display = 'none';
        }
    });
</script>
@endsection