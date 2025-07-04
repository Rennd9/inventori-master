@extends('template')

@section('content')
<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-6">
        <div class="neo-card bg-light p-3 h-100 custom-card" >
            <div class="d-flex justify-content-between">
                <div>
                        @php
                            $totalUser = DB::table('users')->count();
                        @endphp
                    <p class="small fw-medium badge bg-primary ">TOTAL USERS</p>
                    <h3 class="h2 fw-bold">{{ $totalUser }}</h3>
                    <p class="small text-success">+12% from last week</p>
                </div>
                <div class="d-flex align-items-center justify-content-center neo-border text-black" 
                     style="width: 48px; height: 48px; background-color: #ddd6fe;">
                    <i class="fas fa-users fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-6">
        <div class="neo-card bg-light p-3 h-100 custom-card" >
            <div class="d-flex justify-content-between">
                <div>
                    <p class="small fw-medium badge bg-primary ">TOTAL BARANG</p>
                    <h3 class="h2 fw-bold">{{ $totalItems ?? '2,156' }}</h3>
                    <p class="small text-info">Updated today</p>
                </div>
                <div class="d-flex align-items-center justify-content-center neo-border text-black" 
                     style="width: 48px; height: 48px; background-color: #93c5fd;">
                    <i class="fas fa-box fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-6">
        <div class="neo-card bg-light p-3 h-100 custom-card" >
            <div class="d-flex justify-content-between">
                <div>
                    <p class="small fw-medium badge bg-primary ">Barang Keluar</p>
                    <h3 class="h2 fw-bold">{{$barangMasuk}}</h3>
                    <p class="small text-danger">-3% from yesterday</p>
                </div>
                <div class="d-flex align-items-center justify-content-center neo-border text-black" 
                     style="width: 48px; height: 48px; background-color: #86efac;">
                    <i class="fas fa-shopping-cart fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-6">
        <div class="neo-card bg-light-100 p-3 h-100 custom-card" >
            <div class="d-flex justify-content-between">
                <div>
                    <p class="small fw-medium badge bg-primary ">Barang Masuk</p>
                    <h3 class="h2 fw-bold">{{$barangMasuk}}</h3>
                    <p class="small text-success">-15% from last week</p>
                </div>
                <div class="d-flex align-items-center justify-content-center neo-border text-black" 
                     style="width: 48px; height: 48px; background-color:rgb(142, 175, 247) !important;">
                    <i class="fas fa-truck fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection