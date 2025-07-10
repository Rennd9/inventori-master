<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoAdmin - Brutalist Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('localadmin.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<style>
@import url(https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap);.neo-btn,body{position:relative}.main-container,.main-content{display:flex;min-height:100vh}.btn-neo,.modal-neo .form-label,.modal-neo .modal-title{text-transform:uppercase;letter-spacing:.05em}.gambar{background-image:url('https://sdmntprcentralus.oaiusercontent.com/files/00000000-0504-61f5-9940-e65e1d8b3c52/raw?se=2025-07-01T19%3A38%3A37Z&sp=r&sv=2024-08-04&sr=b&scid=bb6e0e55-88a1-559e-b57c-8cd7f5b9a1c1&skoid=e9d2f8b1-028a-4cff-8eb1-d0e66fbefcca&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2025-07-01T16%3A52%3A00Z&ske=2025-07-02T16%3A52%3A00Z&sks=b&skv=2024-08-04&sig=vhUTGUwXkii9%2BVPJA3JwPZIwSpb1gXzssGlu/8oAmUA%3D');background-repeat:no-repeat;background-size:800px 600px;background-position:center;height:100vh;margin:0}.custom-card{background-color:rgba(77 123 161 / 23%)!important}.neo-btn,.neo-card{background:var(--bg-secondary);color:var(--text-primary)}:root{--neo-border:3px solid #000000;--neo-shadow:6px 6px 0px #334155;--neo-shadow-sm:4px 4px 0px #334155;--neo-shadow-lg:8px 8px 0px #334155;--sidebar-width:280px;--bg-primary:#212529;--bg-secondary:rgb(28, 32, 36);--bg-tertiary:#0a121c;--text-primary:#e2e8f0;--text-secondary:#94a3b8;--accent-blue:#64748b;--accent-blue-dark:#475569;--accent-blue-light:#94a3b8}*{box-sizing:border-box}body,html{height:100%;overflow-x:hidden}body{font-family:'Space Grotesk',sans-serif;background-color:var(--bg-primary);margin:0;color:var(--text-primary)}body.sidebar-open{overflow:hidden;position:fixed;width:100%}.loading{opacity:.6;pointer-events:none}.neo-border,.neo-btn,.neo-card{border:var(--neo-border)!important}.neo-border{box-shadow:var(--neo-shadow)!important}.neo-btn{box-shadow:var(--neo-shadow-sm)!important;transition:.2s!important;border-color:var(--accent-blue)!important}.btn-neo:hover,.neo-btn:hover{transform:translate(2px,2px)!important;box-shadow:2px 2px 0 var(--accent-blue-dark)!important;background:var(--bg-tertiary)}.neo-card{box-shadow:var(--neo-shadow)!important;transition:.3s!important}.btn-neo-primary,.btn-neo-secondary{background-color:var(--bg-tertiary)!important;color:var(--text-primary)!important}.neo-card:hover{transform:translate(-2px,-2px)!important;box-shadow:var(--neo-shadow-lg)!important}.btn-neo{font-weight:700!important;padding:.75rem 1.5rem;transition:.2s!important;border-radius:0!important;border:var(--neo-border)!important;box-shadow:var(--neo-shadow-sm)!important}.btn-neo-primary{border-color:var(--accent-blue-dark)!important}.btn-neo-primary:hover{background-color:var(--accent-blue)!important}.btn-neo-secondary{border-color:var(--text-secondary)!important}.btn-neo-success{background-color:#10b981!important;border-color:#059669!important;color:var(--bg-primary)!important}.btn-neo-danger{background-color:#ef4444!important;border-color:#dc2626!important;color:var(--text-primary)!important}.btn-neo-warning{background-color:#f59e0b!important;border-color:#d97706!important;color:var(--bg-primary)!important}.btn-neo-light{background-color:var(--bg-secondary)!important;color:var(--text-primary)!important;border-color:var(--text-secondary)!important}.main-container{flex-direction:column}.main-content{flex:1;flex-direction:column}.top-nav{background:var(--bg-secondary);border-bottom:var(--neo-border);padding:1rem;position:sticky;top:0;z-index:1030}.hamburger-btn{border:none;background:0 0;padding:8px;cursor:pointer;transition:transform .3s;outline:0;color:var(--text-secondary)}.hamburger-btn:hover{transform:scale(1.1);color:var(--text-primary)}.content-area{flex:1;padding:1rem;overflow-y:auto}.sidebar,.sidebar-backdrop{height:100vh;position:fixed;top:0;left:0}.sidebar{background:var(--bg-secondary);border-right:var(--neo-border);box-shadow:var(--neo-shadow);overflow-y:auto;width:var(--sidebar-width);z-index:1050;transform:translateX(-100%);transition:transform .3s ease-in-out}.sidebar.show{transform:translateX(0)}.sidebar-item{transition:.2s;cursor:pointer;border-radius:0;display:block;text-decoration:none!important;color:var(--text-primary)!important;border:none;background:0 0;width:100%;text-align:left}.sidebar-item:hover{background-color:var(--bg-primary)!important;transform:translateX(5px)}.sidebar-item.active{background-color:var(--accent-blue-light)!important;border:var(--neo-border)!important;color:var(--bg-primary)!important}.sidebar-dropdown{background-color:var(--bg-tertiary);border-left:var(--neo-border);margin-left:1rem;transition:.3s}.dropdown-toggle::after{content:"▶";border:none;font-size:.8rem;margin-left:auto;transition:transform .2s;color:var(--text-secondary)}.dropdown-toggle:not(.collapsed)::after{transform:rotate(90deg)}.sidebar-backdrop{width:100vw;background-color:rgba(0,0,0,.8);z-index:1040;opacity:0;visibility:hidden;transition:.3s}.sidebar-backdrop.show{opacity:1;visibility:visible}.close-sidebar-btn{position:absolute;top:15px;right:15px;background:0 0;border:none;font-size:18px;cursor:pointer;z-index:1051;color:var(--text-secondary);width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:50%;transition:background-color .2s;outline:0}.form-control,.form-select,.select2-dropdown{border:var(--neo-border)!important;box-shadow:var(--neo-shadow-sm)!important}.close-sidebar-btn:hover{background-color:var(--bg-tertiary);color:var(--text-primary)}.form-control,.form-select{background-color:var(--bg-secondary)!important;color:var(--text-primary)!important;border-radius:0!important}.form-control:focus,.form-select:focus{background-color:var(--bg-tertiary)!important;border-color:var(--accent-blue)!important;box-shadow:var(--neo-shadow-sm)!important}.dropdown-menu,.select2-dropdown{background-color:var(--bg-secondary)!important}.select2-container{width:100%!important}.select2-dropdown{color:var(--text-primary)!important;border-radius:0!important}.select2-container--default .select2-selection--single{height:auto;border:var(--neo-border)!important;box-shadow:var(--neo-shadow-sm)!important;background-color:var(--bg-secondary)!important;color:var(--text-primary)!important;border-radius:0!important;padding:.5rem 1rem}.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:1.5;color:var(--text-primary)!important}.select2-container--default .select2-selection--single .select2-selection__arrow b{border-color:var(--text-secondary) transparent transparent transparent!important}.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{border-color:transparent transparent var(--text-secondary) transparent!important}.select2-search--dropdown .select2-search__field{border:var(--neo-border)!important;background-color:var(--bg-tertiary)!important;color:var(--text-primary)!important;border-radius:0!important;padding:6px 8px;width:100%}.select2-container--default .select2-results__option{padding:8px 12px}.select2-container--default .select2-results__option--highlighted[aria-selected]{background-color:var(--accent-blue)!important;color:var(--bg-primary)!important}.dropdown-item,.dropdown-menu,.modal-neo .modal-content,.select2-container--default .select2-results__group{color:var(--text-primary)!important}.select2-container--default .select2-results__option[aria-selected=true]{background-color:var(--bg-tertiary)!important;color:var(--text-primary)!important}.select2-container--default .select2-results__group{background-color:var(--bg-primary)!important;font-weight:600;padding:6px 12px}.dropdown-menu{border:var(--neo-border)!important;box-shadow:var(--neo-shadow)!important;max-width:90vw;border-radius:0!important}.dropdown-item:focus,.dropdown-item:hover{background-color:var(--bg-tertiary)!important}.notification-dot{width:8px;height:8px;background-color:#ef4444;border:2px solid var(--bg-secondary);border-radius:50%}.modal-neo .modal-dialog{margin:2rem auto}.modal-neo .modal-content{border:var(--neo-border)!important;box-shadow:var(--neo-shadow-lg)!important;border-radius:0!important;background-color:var(--bg-secondary)!important;overflow:hidden}.modal-neo .modal-header{background-color:var(--bg-tertiary)!important;border-bottom:var(--neo-border)!important;padding:1.5rem 2rem}.modal-neo .modal-title{font-size:1.5rem;color:var(--text-primary);font-weight:700!important}.modal-neo .btn-close{background:var(--bg-tertiary)!important;color:var(--text-primary)!important;width:40px;height:40px;box-shadow:var(--neo-shadow-sm)!important;font-size:1.2rem;font-weight:700;display:flex;align-items:center;justify-content:center;transition:.2s;border:var(--neo-border)!important;opacity:1!important;border-radius:0}.modal-neo .btn-close:hover{background-color:#ef4444!important;color:var(--text-primary)!important;transform:translate(2px,2px);box-shadow:2px 2px 0 var(--accent-blue-dark)!important}.modal-neo .modal-body{padding:2rem;font-weight:500;line-height:1.6}.modal-neo .modal-footer{border-top:var(--neo-border)!important;padding:1.5rem 2rem;background-color:var(--bg-tertiary)}.modal-neo .form-label{font-weight:700;font-size:.875rem;margin-bottom:.5rem;color:var(--text-secondary)}.modal-neo .form-control{font-weight:500;padding:.75rem 1rem;background-color:var(--bg-tertiary)!important}.modal-neo .form-control:focus{background-color:var(--bg-secondary)!important}.bg-red-100{background-color:#7f1d1d!important}.bg-yellow-200{background-color:#92400e!important}.text-purple-600{color:#a855f7!important}@media (max-width:575.98px){.content-area,.top-nav{padding:.75rem}.dropdown-menu{max-width:95vw}.notification-dropdown,.user-dropdown{min-width:auto;width:90vw;max-width:300px}.btn-neo,.neo-btn{padding:.5rem .75rem;font-size:.9rem}.sidebar{width:90vw;max-width:300px}.mobile-search-hide{display:none!important}.hamburger-btn{min-width:44px;min-height:44px}}@media (min-width:992px){.sidebar{position:relative;transform:translateX(0);flex-shrink:0}.close-sidebar-btn,.hamburger-btn,.sidebar-backdrop{display:none!important}.main-container{flex-direction:row}}.select2-container--default .select2-results__option--selected{background-color:#000}@media (prefers-reduced-motion:reduce){*,::after,::before{animation-duration:0s!important;animation-iteration-count:1!important;transition-duration:0s!important}}
    </style>
<body>
    
    <div class="main-container">
        <!-- Sidebar Backdrop -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar d-flex flex-column" id="mobileSidebar">
            <button class="close-sidebar-btn" id="closeSidebarBtn">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Sidebar Header -->
            <div class="p-3 border-bottom border-black border-3">
                <h1 class="h4 fw-bold mb-1">Dashboard
                    @if(auth()->user()->type == 'admin')
                        <span class="text-purple-600">ADMIN</span>
                    @elseif(auth()->user()->type == 'cheff')
                        <span class="text-success">CHEFF</span>
                    @else
                        <span class="text-primary">Barista</span>
                    @endif
                </h1>
            </div>
            
            <!-- Sidebar Menu -->
            <div class="flex-grow-1 overflow-auto p-3">
                
                @if(auth()->user()->type == 'admin')
                    <!-- Admin Dashboard -->
                    <a href="{{ route('admin.home') }}" class="text-decoration-none">
                        <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </div>
                    </a>
                    <!-- Laporan -->
                    <a href="{{ route('laporan.index') }}" class="text-decoration-none">
                        <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt me-2"></i> Cetak Laporan
                        </div>
                    </a>
                    <!-- Data Master Dropdown -->
                    <div class="mb-2">
                        <button class="sidebar-item p-3 fw-medium d-flex align-items-center {{ request()->routeIs('kategori.*', 'items.*', 'suppliers.*', 'customers.*') ? '' : 'collapsed' }} dropdown-toggle" 
                                data-bs-toggle="collapse" data-bs-target="#dataMasterDropdown" 
                                aria-expanded="{{ request()->routeIs('kategori.*', 'items.*', 'suppliers.*', 'customers.*') ? 'true' : 'false' }}">
                            <i class="fas fa-database me-2"></i> Data Master
                        </button>
                        <div class="collapse sidebar-dropdown {{ request()->routeIs('kategori.*', 'items.*', 'suppliers.*', 'customers.*') ? 'show' : '' }}" id="dataMasterDropdown">
                            <a href="{{ route('kategori.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                                    <i class="fas fa-tags me-2"></i> Kategori Barang
                                </div>
                            </a>
                            <a href="{{ route('items.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('items.*') ? 'active' : '' }}">
                                    <i class="fas fa-box me-2"></i> Data Barang
                                </div>
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                                    <i class="fas fa-truck me-2"></i> Data Suppliers
                                </div>
                            </a>
                            <a href="{{ route('customers.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                    <i class="fas fa-users me-2"></i> Data Karyawan
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Transaksi Dropdown -->
                    <div class="mb-2">
                        <button class="sidebar-item p-3 fw-medium d-flex align-items-center {{ request()->routeIs('barang-keluar.*', 'barang-masuk.*') ? '' : 'collapsed' }} dropdown-toggle" 
                                data-bs-toggle="collapse" data-bs-target="#transaksiDropdown" 
                                aria-expanded="{{ request()->routeIs('barang-keluar.*', 'barang-masuk.*') ? 'true' : 'false' }}">
                            <i class="fas fa-exchange-alt me-2"></i> Transaksi Barang
                        </button>
                        <div class="collapse sidebar-dropdown {{ request()->routeIs('barang-keluar.*', 'barang-masuk.*') ? 'show' : '' }}" id="transaksiDropdown">
                            <a href="{{ route('barang-keluar.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart me-2"></i> Barang Keluar
                                </div>
                            </a>
                            <a href="{{ route('barang-masuk.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                                    <i class="fas fa-truck me-2"></i> Barang Masuk
                                </div>
                            </a>
                        </div>
                    </div>
                    
                   <!-- Permission & User Auth Management -->
                    <div class="mb-2">
                        <button class="sidebar-item p-3 fw-medium d-flex align-items-center {{ request()->routeIs('admin.users.categories.*', 'users.index*') ? '' : 'collapsed' }} dropdown-toggle"
                                data-bs-toggle="collapse" data-bs-target="#permissionDropdown"
                                aria-expanded="{{ request()->routeIs('admin.users.categories.*', 'users.index*') ? 'true' : 'false' }}">
                            <i class="fas fa-user-cog me-2"></i> Manajemen Akses
                        </button>
                        <div class="collapse sidebar-dropdown {{ request()->routeIs('admin.users.categories.*', 'users.index*') ? 'show' : '' }}" id="permissionDropdown">
                            <!-- Permission Barang -->
                            <a href="{{ route('admin.users.categories.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('admin.users.categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-key me-2"></i> Permission Barang
                                </div>
                            </a>

                            <!-- User Auth -->
                            <a href="{{ route('users.index') }}" class="text-decoration-none">
                                <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('users*') ? 'active' : '' }}">
                                    <i class="fas fa-user-lock me-2"></i> User Auth
                                </div>
                            </a>
                        </div>
                    </div>

                @elseif(auth()->user()->type == 'cheff')
                    <!-- Cheff Dashboard -->
                     <a href="{{ route('cheff.home') }}" class="text-decoration-none">
                    <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('cheff.home') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </div>
                </a>

                <!-- Cheff Items Management -->
                <a href="{{ route('cheff.items.index') }}" class="text-decoration-none">
                    <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('cheff.items.*') ? 'active' : '' }}">
                        <i class="fas fa-box me-2"></i> Data Barang
                    </div>
                </a>

                <!-- Transaksi Barang -->
                <div class="mb-2">
                    <button class="sidebar-item p-3 fw-medium d-flex align-items-center {{ request()->routeIs('cheff.barang-keluar.*', 'cheff.barang-masuk.*') ? '' : 'collapsed' }} dropdown-toggle"
                            data-bs-toggle="collapse" data-bs-target="#transaksiDropdown"
                            aria-expanded="{{ request()->routeIs('cheff.barang-keluar.*', 'cheff.barang-masuk.*') ? 'true' : 'false' }}">
                        <i class="fas fa-exchange-alt me-2"></i> Transaksi Barang
                    </button>
                    <div class="collapse sidebar-dropdown {{ request()->routeIs('cheff.barang-keluar.*', 'cheff.barang-masuk.*') ? 'show' : '' }}" id="transaksiDropdown">
                    
                        <!-- Barang Keluar -->
                        <a href="{{ route('cheff.barang-keluar.index') }}" class="text-decoration-none">
                            <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('cheff.barang-keluar.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart me-2"></i> Barang Keluar
                            </div>
                        </a>

                        <!-- Barang Masuk -->
                        <a href="{{ route('cheff.barang-masuk.index') }}" class="text-decoration-none">
                            <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('cheff.barang-masuk.*') ? 'active' : '' }}">
                                <i class="fas fa-truck me-2"></i> Barang Masuk
                            </div>
                        </a>
                        
                        </div>
                    </div>
                  

                @else
                    <!-- User Dashboard -->
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </div>
                    </a>
                    <!-- User Items Management -->
                <a href="{{ route('user.items.index') }}" class="text-decoration-none">
                    <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('user.items.*') ? 'active' : '' }}">
                        <i class="fas fa-box me-2"></i> Data Barang
                    </div>
                </a>
                    <div class="mb-2">
                    <button class="sidebar-item p-3 fw-medium d-flex align-items-center {{ request()->routeIs('users.barang-keluar.*', 'users.barang-masuk.*') ? '' : 'collapsed' }} dropdown-toggle"
                            data-bs-toggle="collapse" data-bs-target="#transaksiDropdown"
                            aria-expanded="{{ request()->routeIs('users.barang-keluar.*', 'users.barang-masuk.*') ? 'true' : 'false' }}">
                        <i class="fas fa-exchange-alt me-2"></i> Transaksi Barang
                    </button>
                    <div class="collapse sidebar-dropdown {{ request()->routeIs('users.barang-keluar.*', 'users.barang-masuk.*') ? 'show' : '' }}" id="transaksiDropdown">
                    
                        <!-- Barang Keluar -->
                        <a href="{{ route('users.barang-keluar.index') }}" class="text-decoration-none">
                            <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('users.barang-keluar.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart me-2"></i> Barang Keluar
                            </div>
                        </a>

                        <!-- Barang Masuk -->
                        <a href="{{ route('users.barang-masuk.index') }}" class="text-decoration-none">
                            <div class="sidebar-item p-3 fw-medium mb-2 text-black {{ request()->routeIs('users.barang-masuk.*') ? 'active' : '' }}">
                                <i class="fas fa-truck me-2"></i> Barang Masuk
                            </div>
                        </a>
                        </div>
                    </div>
                   
                @endif
               
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation -->
            <header class="top-nav d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="hamburger-btn d-lg-none me-3" id="sidebarToggle">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    <h2 class="h4 fw-bold mb-0">{{ $pageTitle ?? 'DASHBOARD OVERVIEW' }}</h2>
                </div>
                
                <div class="d-flex align-items-center gap-2 gap-md-3">
                  
                    
                    @php
    use Illuminate\Support\Facades\DB;

    $restockNotifications = DB::table('restock_requests')
        ->join('items', 'restock_requests.item_id', '=', 'items.id') // JOIN ke tabel items
        ->where('restock_requests.status', 'pending')
        ->orderBy('restock_requests.created_at', 'desc')
        ->select('restock_requests.*', 'items.name as item_name') // ambil nama barang
        ->limit(4)
        ->get();

    $restockCount = $restockNotifications->count();
@endphp



                    <!-- Notifications -->
<div class="dropdown">
    <button id="notificationBtn" class="btn neo-btn position-relative" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if($restockCount > 0)
            <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-black">
                {{ $restockCount }}
            </span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end neo-border" style="width: 300px; max-width: 90vw;">
        <li class="dropdown-header fw-bold">
            <i class="fas fa-bell me-2"></i> Notifications
        </li>
        <li><hr class="dropdown-divider"></li>

        @forelse ($restockNotifications as $notif)
            <li>
                <a class="dropdown-item py-2" href="#">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-2">
                            <i class="fas fa-box text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-medium">{{ $notif->message }}</div>
                            <div class="small text-muted">Nama: {{ Str::limit($notif->item_name, 28, '...') }}</div>
                            <div class="small text-muted">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li><div class="dropdown-item text-muted">Tidak ada notifikasi</div></li>
        @endforelse

        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item text-center fw-medium" href="#"> <i class="fas fa-eye me-1"></i> Lihat Semua Notifikasi </a>
        </li>
    </ul>
</div>

<!-- User Dropdown -->
@php
    $user = auth()->user();
    $avatar = $user && $user->image ? asset('storage/users/' . $user->image) : 'https://i.pravatar.cc/32';
@endphp

<div class="dropdown">
    <button class="btn neo-btn dropdown-toggle d-flex align-items-center" type="button" 
            data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ $avatar }}" 
             class="rounded-circle border border-2 border-black me-2" 
             width="32" height="32" alt="Avatar">
        <span class="d-none d-md-inline">{{ $user->name ?? 'User' }}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end neo-border">
        <li class="dropdown-header">
            <div class="d-flex align-items-center">
                <img src="{{ $avatar }}" 
                     class="rounded-circle border border-2 border-black me-2" 
                     width="40" height="40" alt="Avatar">
                <div>
                    <div class="fw-bold">{{ $user->name ?? 'User' }}</div>
                    <div class="small text-muted">{{ $user->email ?? 'user@example.com' }}</div>
                </div>
            </div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                <i class="fas fa-user me-2"></i> My Profile
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                <i class="fas fa-edit me-2"></i> Edit Profile
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>

                </div>
            </header>
            
            <!-- Dashboard Content -->
            <div class="content-area gambar">
                @yield('content')
            </div>
        </main>
    </div>
<!-- JQuery Select2 dan script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('mobileSidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const body = document.body;
            
            function showSidebar() {
                sidebar.classList.add('show');
                sidebarBackdrop.classList.add('show');
                body.classList.add('sidebar-open');
            }
            
            function hideSidebar() {
                sidebar.classList.remove('show');
                sidebarBackdrop.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
            
            sidebarToggle?.addEventListener('click', (e) => {
                e.preventDefault();
                sidebar.classList.contains('show') ? hideSidebar() : showSidebar();
            });
            
            closeSidebarBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                hideSidebar();
            });
            
            sidebarBackdrop?.addEventListener('click', hideSidebar);
            
            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    hideSidebar();
                }
            });
            
            // Auto-close on resize to desktop
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    if (window.innerWidth >= 992) {
                        hideSidebar();
                    }
                }, 100);
            });
            
            // Touch gestures for mobile
            let touchStartX = 0;
            let touchStartY = 0;
            
            document.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            });
            
            document.addEventListener('touchend', (e) => {
                if (window.innerWidth >= 992) return;
                
                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                const deltaX = touchEndX - touchStartX;
                const deltaY = touchEndY - touchStartY;
                
                // Swipe right to open sidebar (from left edge)
                if (deltaX > 50 && Math.abs(deltaY) < 100 && touchStartX < 20 && !sidebar.classList.contains('show')) {
                    showSidebar();
                }
                
                // Swipe left to close sidebar
                if (deltaX < -50 && Math.abs(deltaY) < 100 && sidebar.classList.contains('show')) {
                    hideSidebar();
                }
            });
            
            // Improved dropdown handling for mobile
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    // Ensure dropdown works properly on mobile
                    if (window.innerWidth < 992) {
                        e.stopPropagation();
                    }
                });
            });
            
            // Performance optimization: Debounce scroll events
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    // Handle scroll-based interactions here if needed
                }, 10);
            });
        });
        $(document).ready(function () {
            $(".js-example-disabled-results").select2({
                allowClear: true
            });
        });
        </script>
</body>
</html>