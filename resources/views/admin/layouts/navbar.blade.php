<!-- Navbar Start -->
@php
    $randomAvatar = rand(1, 7); // Pick a random number between 1 and 7
@endphp
<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
    <a class="navbar-brand d-flex d-lg-none me-4">
        <img src="{{ asset('logo/logo_solid.png') }}" alt="Logo" style="width: 50px; height: 50px;">
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>

    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item dropdown">
            <a href="#" class="nav-link" data-bs-toggle="dropdown" style="text-decoration: none;">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                        @if (auth()->user()->role->name === 'admin')
                            <img src="{{ asset('logo/admin.jpg') }}" alt="Avatar" class="rounded-circle" />
                        @elseif (auth()->user()->role->name === 'doctor')
                            <img src="{{ asset('logo/doctor.jpg') }}" alt="Avatar" class="rounded-circle" />
                            @elseif (auth()->user()->role->name === 'patient')
                            <img src="{{ asset('logo/patient.webp') }}" alt="Avatar" class="rounded-circle" />
                            @endif
                    </div>
                    <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                {{-- <a href="{{ route('profile.edit') }}" class="dropdown-item">My Profile</a>
                <a href="#" class="dropdown-item">Settings</a> --}}
                <a href="{{ route('logout') }}" class="dropdown-item">Log Out</a>
            </div>
        </div>
    </div>
</nav>
<!-- Navbar End -->
