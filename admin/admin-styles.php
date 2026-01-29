<style>
    :root { --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); --active-blue: #10b981; --bs-primary: #10b981; --bs-primary-rgb: 16, 185, 129; }
    body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%); margin: 0; }
    
    /* Animated Background Blobs */
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25% { transform: translate(20px, -20px) scale(1.1); }
        50% { transform: translate(-20px, 20px) scale(0.9); }
        75% { transform: translate(20px, 20px) scale(1.05); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
    
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 25px 0;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        overflow: hidden;
    }
    .sidebar-brand { 
        color: var(--active-blue); 
        font-weight: 700; 
        font-size: 1.25rem;
        padding: 0 20px 25px;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sidebar-menu { 
        list-style: none; 
        padding: 0 15px; 
        padding-bottom: 80px;
        margin: 0; 
        flex-grow: 1;
        overflow-y: auto;
    }
    .sidebar-menu::-webkit-scrollbar { width: 6px; }
    .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    .sidebar-menu li { margin-bottom: 6px; }
    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 14px 18px;
        border-radius: 12px;
        text-decoration: none;
        color: #94a3b8;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar-menu a i { font-size: 1.2rem; }
    .sidebar-menu a:hover, .sidebar-menu a.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .logout-section {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 280px;
        padding: 20px 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.2) 100%);
        flex-shrink: 0;
    }
    .logout-section a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 14px 18px;
        border-radius: 12px;
        text-decoration: none;
        color: #ef4444;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .logout-section a i { font-size: 1.2rem; }
    .logout-section a:hover {
        background: rgba(239, 68, 68, 0.15);
        transform: translateX(5px);
    }
    .main-content { 
        margin-left: 280px; 
        padding: 25px 40px;
        position: relative;
        min-height: 100vh;
    }
    /* Konsistensi heading utama di halaman admin */
    .main-content > .mb-4:first-child,
    .main-content > .mb-5:first-child {
        margin-top: 0;
    }
    .main-content > .mb-4.page-header-lift {
        margin-top: -32px;
    }
    .main-content > .mb-4:first-child h1.fw-bold,
    .main-content > .mb-4:first-child h2.fw-bold,
    .main-content > .mb-4:first-child h3.fw-bold,
    .main-content > .mb-5:first-child h1.fw-bold,
    .main-content > .mb-5:first-child h2.fw-bold,
    .main-content > .mb-5:first-child h3.fw-bold {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        margin-bottom: 0.35rem;
        line-height: 1.2;
    }
    .main-content > .mb-4:first-child p.text-muted,
    .main-content > .mb-5:first-child p.text-muted {
        margin-bottom: 0;
        color: #4b5563;
    }
    /* Animated Background Decorations */
    .main-content::before {
        content: '';
        position: fixed;
        top: 0;
        right: 0;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        filter: blur(40px);
        animation: blob 10s infinite;
        z-index: 0;
        pointer-events: none;
    }
    .main-content::after {
        content: '';
        position: fixed;
        bottom: 0;
        left: 280px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        filter: blur(40px);
        animation: blob 12s infinite;
        animation-delay: 2s;
        z-index: 0;
        pointer-events: none;
    }
    .card {
        position: relative;
        z-index: 1;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.8) !important;
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: none !important;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3) !important;
    }
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: none !important;
    }
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: none !important;
    }
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        border: none !important;
    }
    .btn-info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
        border: none !important;
    }
    .table {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    .table thead {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(16, 185, 129, 0.05);
    }
    .form-control, .form-select, textarea {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s;
        font-family: 'Inter', sans-serif;
        line-height: 1.5;
    }
    .form-control:focus, .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .modal-content {
        border-radius: 24px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }
    .modal-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 24px 24px 0 0;
        border-bottom: none;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
    }
    .section-heading {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0;
        line-height: 1.3;
    }
    .page-header {
        position: relative;
        z-index: 1;
        margin-bottom: 2rem;
    }
    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    .page-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1rem;
    }
</style>
