<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Stationery POS'); ?> - <?php echo e(config('app.name')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --sidebar-width: 260px;
            --header-height: 60px;
        }
        
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            background: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand h4 {
            margin: 0;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-brand i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }
        
        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-top: 1rem;
        }
        
        .sidebar-nav .nav-link {
            color: #94a3b8;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(79, 70, 229, 0.2);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 24px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .top-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .page-content {
            padding: 1.5rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 0.75rem;
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }
        
        .stat-card {
            background: #fff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #dcfce7; color: #16a34a; }
        .stat-icon.yellow { background: #fef3c7; color: #d97706; }
        .stat-icon.red { background: #fee2e2; color: #dc2626; }
        .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
        
        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
        }
        
        .stat-info p {
            margin: 0;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .table th {
            font-weight: 600;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom-width: 1px;
        }
        
        .badge-success { background-color: #dcfce7; color: #16a34a; }
        .badge-warning { background-color: #fef3c7; color: #d97706; }
        .badge-danger { background-color: #fee2e2; color: #dc2626; }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-pencil-square"></i> Stationery POS</h4>
        </div>
        <nav class="sidebar-nav">
            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            <div class="nav-section">Sales</div>
            <a class="nav-link <?php echo e(request()->routeIs('pos.index') ? 'active' : ''); ?>" href="<?php echo e(route('pos.index')); ?>">
                <i class="bi bi-cart3"></i> Point of Sale
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('pos.history') ? 'active' : ''); ?>" href="<?php echo e(route('pos.history')); ?>">
                <i class="bi bi-clock-history"></i> Sales History
            </a>
            
            <div class="nav-section">Inventory</div>
            <a class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>" href="<?php echo e(route('categories.index')); ?>">
                <i class="bi bi-folder"></i> Categories
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" href="<?php echo e(route('products.index')); ?>">
                <i class="bi bi-box-seam"></i> Products
            </a>
            
            <div class="nav-section">Reports</div>
            <a class="nav-link <?php echo e(request()->routeIs('reports.daily') ? 'active' : ''); ?>" href="<?php echo e(route('reports.daily')); ?>">
                <i class="bi bi-calendar-day"></i> Daily Report
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('reports.monthly') ? 'active' : ''); ?>" href="<?php echo e(route('reports.monthly')); ?>">
                <i class="bi bi-calendar-month"></i> Monthly Report
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('reports.inventory') ? 'active' : ''); ?>" href="<?php echo e(route('reports.inventory')); ?>">
                <i class="bi bi-clipboard-data"></i> Inventory Report
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('reports.profit') ? 'active' : ''); ?>" href="<?php echo e(route('reports.profit')); ?>">
                <i class="bi bi-graph-up-arrow"></i> Profit Report
            </a>
            
            <div class="nav-section">System</div>
            <a class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>" href="<?php echo e(route('settings.index')); ?>">
                <i class="bi bi-gear"></i> Settings
            </a>
        </nav>
    </aside>
    
    <main class="main-content">
        <header class="top-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link d-lg-none text-dark" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h5>
            </div>
            <div class="dropdown">
                <div class="user-dropdown" data-bs-toggle="dropdown">
                    <div class="user-avatar"><?php echo e(substr(auth()->user()->name ?? 'A', 0, 1)); ?></div>
                    <div class="d-none d-md-block">
                        <div class="fw-semibold"><?php echo e(auth()->user()->name ?? 'Admin'); ?></div>
                        <small class="text-muted"><?php echo e(ucfirst(auth()->user()->role ?? 'admin')); ?></small>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('settings.index')); ?>">
                            <i class="bi bi-gear me-2"></i> Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>
        
        <div class="page-content">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Global currency symbol for JavaScript
        window.currencySymbol = '<?php echo e($currency ?? "৳"); ?>';
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\mukul\workspace\pos\resources\views/layouts/app.blade.php ENDPATH**/ ?>