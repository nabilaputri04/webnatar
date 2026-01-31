<?php
// Prevent caching untuk memastikan style selalu update
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: text/css");
?>
<style>
    :root { 
        --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); 
        --active-blue: #10b981; 
        --bs-primary: #10b981; 
        --bs-primary-rgb: 16, 185, 129;
        --sidebar-width: 280px;
    }
    
    * {
        box-sizing: border-box;
    }
    
    body { 
        font-family: 'Inter', sans-serif; 
        background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%); 
        margin: 0; 
        overflow-x: hidden;
        overflow-y: auto;
        position: relative;
        min-height: 100vh;
    }
    
    body.modal-open {
        overflow-y: auto !important;
        padding-right: 0 !important;
    }
    
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
    
    /* Mobile Navigation Bar */
    .mobile-nav {
        display: none;
        background: #fff;
        padding: 12px 20px;
        border-bottom: 2px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 9997;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .mobile-nav .fw-bold {
        font-size: 1.1rem;
        color: #10b981;
    }
    
    /* Mobile Menu Toggle Button - Universal untuk semua implementasi */
    .mobile-menu-toggle,
    .mobile-nav button,
    #sidebarToggle {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        border: none !important;
        width: 44px !important;
        height: 44px !important;
        border-radius: 10px !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
        transition: all 0.3s;
        -webkit-tap-highlight-color: transparent;
        padding: 0 !important;
    }
    .mobile-menu-toggle:active,
    .mobile-nav button:active,
    #sidebarToggle:active {
        transform: scale(0.95) !important;
    }
    .mobile-menu-toggle:hover,
    .mobile-nav button:hover,
    #sidebarToggle:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4) !important;
    }
    .mobile-menu-toggle i,
    .mobile-nav button i,
    #sidebarToggle i {
        font-size: 1.4rem;
    }
    
    /* Overlay untuk mobile */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        -webkit-backdrop-filter: blur(2px);
        backdrop-filter: blur(2px);
    }
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 25px 0;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        z-index: 9999;
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
        margin-left: var(--sidebar-width); 
        padding: 30px 40px;
        position: relative;
        min-height: 100vh;
        z-index: 1;
        width: calc(100% - var(--sidebar-width));
        overflow-y: auto;
        max-height: 100vh;
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
        z-index: 10;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.8) !important;
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
        z-index: 100;
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
        font-size: 0.95rem;
    }
    .table thead {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(16, 185, 129, 0.05);
    }
    /* Responsive table wrapper */
    .table-responsive {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background: white;
        overflow-x: auto;
        overflow-y: auto;
        max-height: 70vh;
        -webkit-overflow-scrolling: touch;
        position: relative;
    }
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #10b981;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #059669;
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
        position: relative;
        z-index: 10003 !important;
        pointer-events: auto !important;
    }
    .modal-backdrop {
        z-index: 10000 !important;
        pointer-events: none !important;
    }
    .modal {
        z-index: 10001 !important;
        pointer-events: none !important;
    }
    .modal-dialog {
        z-index: 10002 !important;
        position: relative;
        pointer-events: auto !important;
    }
    .modal-body,
    .modal-body * {
        pointer-events: auto !important;
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
    
    /* ========== RESPONSIVE DESIGN FOR MOBILE ========== */
    @media (max-width: 992px) {
        :root {
            --sidebar-width: 280px;
        }
        
        /* Hide sidebar by default on mobile */
        .sidebar {
            transform: translateX(-100%);
        }
        .sidebar.active {
            transform: translateX(0);
            box-shadow: 6px 0 30px rgba(0, 0, 0, 0.3);
        }
        
        /* Show mobile navigation and toggle button */
        .mobile-nav {
            display: flex !important;
        }
        .mobile-menu-toggle {
            display: flex !important;
        }
        #sidebarToggle,
        .mobile-nav button {
            display: flex !important;
        }
        
        /* Adjust main content */
        .main-content {
            margin-left: 0 !important;
            padding: 85px 20px 20px 20px !important;
            width: 100% !important;
        }
        
        /* Adjust logout section */
        .logout-section {
            width: var(--sidebar-width);
        }
        
        /* Responsive page title */
        .page-title {
            font-size: 1.5rem !important;
        }
        
        /* Responsive cards */
        .card {
            margin-bottom: 1rem;
        }
        
        /* Stack buttons vertically on mobile */
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .btn-group .btn {
            width: 100%;
            margin: 0;
        }
        
        /* Make action buttons more touch friendly */
        .btn {
            min-height: 44px;
            padding: 0.625rem 1rem !important;
        }
        
        .btn-sm {
            min-height: 38px;
            padding: 0.5rem 0.875rem !important;
        }
    }
    
    @media (max-width: 768px) {
        /* Further adjustments for smaller phones */
        .main-content {
            padding: 75px 16px 16px 16px !important;
        }
        
        .page-title, 
        h1, h2 {
            font-size: 1.35rem !important;
        }
        
        h3, h4 {
            font-size: 1.1rem !important;
        }
        
        .section-heading {
            font-size: 1rem !important;
        }
        
        /* Responsive form controls */
        .form-control, .form-select, textarea, input {
            font-size: 16px !important;
            padding: 0.625rem 0.875rem !important;
            min-height: 44px;
        }
        
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        /* Responsive modal */
        .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
        
        .modal-content {
            border-radius: 16px !important;
        }
        
        .modal-body {
            padding: 1.25rem;
        }
        
        /* Responsive stat cards */
        .col-md-3, .col-md-4, .col-md-6, .col-lg-3, .col-lg-4, .col-lg-6 {
            margin-bottom: 1rem;
        }
        
        /* Stack action buttons */
        .d-flex.gap-2,
        .d-flex.gap-3 {
            flex-direction: column !important;
            gap: 0.75rem !important;
        }
        .d-flex.gap-2 .btn,
        .d-flex.gap-3 .btn {
            width: 100% !important;
        }
        
        /* Responsive table - hide less important columns */
        .table {
            font-size: 0.85rem;
        }
        
        .table td, .table th {
            padding: 0.5rem 0.375rem;
            font-size: 0.85rem;
            vertical-align: middle;
        }
        
        /* Make action buttons stack on mobile */
        .table .btn {
            padding: 0.375rem 0.625rem !important;
            font-size: 0.8rem;
            margin: 0.125rem 0;
            min-height: 32px;
        }
        
        /* Responsive images in tables */
        .table img {
            max-width: 50px;
            height: auto;
        }
        
        /* Card body padding */
        .card-body {
            padding: 1rem !important;
        }
        
        /* Badge responsive */
        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.625rem;
        }
        
        /* Breadcrumb responsive */
        .breadcrumb {
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        /* Extra small devices */
        .mobile-menu-toggle {
            width: 44px;
            height: 44px;
            top: 12px;
            left: 12px;
        }
        
        .main-content {
            padding: 68px 12px 12px 12px !important;
        }
        
        .card-body {
            padding: 0.875rem !important;
        }
        
        .table {
            font-size: 0.75rem;
        }
        
        .table td, .table th {
            padding: 0.375rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .btn {
            padding: 0.5rem 0.875rem !important;
            font-size: 0.85rem;
            min-height: 42px;
        }
        
        .btn-sm {
            padding: 0.375rem 0.625rem !important;
            font-size: 0.75rem;
            min-height: 36px;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Responsive heading */
        h1, h2, .page-title {
            font-size: 1.25rem !important;
            line-height: 1.3;
        }
        
        h3, h4 {
            font-size: 1rem !important;
        }
        
        h5, h6 {
            font-size: 0.9rem !important;
        }
        
        /* Compact spacing */
        .mb-3, .my-3 {
            margin-bottom: 0.875rem !important;
        }
        
        .mb-4, .my-4 {
            margin-bottom: 1rem !important;
        }
        
        .mb-5, .my-5 {
            margin-bottom: 1.25rem !important;
        }
        
        /* Form adjustments */
        .form-control, .form-select, textarea, input {
            font-size: 16px !important;
            padding: 0.625rem !important;
        }
        
        .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.375rem;
        }
        
        /* Modal full screen on very small devices */
        .modal-dialog {
            margin: 0;
            max-width: 100%;
            height: 100%;
        }
        
        .modal-content {
            height: 100%;
            border-radius: 0 !important;
        }
        
        /* Improve touch targets */
        a, button, .btn, input[type="submit"], input[type="button"] {
            -webkit-tap-highlight-color: transparent;
        }
    }
    
    /* Landscape orientation fixes */
    @media (max-height: 600px) and (orientation: landscape) {
        .sidebar {
            overflow-y: auto;
        }
        
        .sidebar-menu {
            padding-bottom: 100px;
        }
        
        .main-content {
            padding-top: 70px !important;
        }
    }
    
    /* ========== UTILITY CLASSES FOR MOBILE ========== */
    /* Hide on mobile */
    @media (max-width: 768px) {
        .mobile-hide {
            display: none !important;
        }
        
        .mobile-text-center {
            text-align: center !important;
        }
        
        .mobile-w-100 {
            width: 100% !important;
        }
        
        /* Responsive flex utilities */
        .flex-mobile-column {
            flex-direction: column !important;
        }
        
        /* Responsive spacing */
        .mobile-p-2 {
            padding: 0.5rem !important;
        }
        
        .mobile-mb-2 {
            margin-bottom: 0.5rem !important;
        }
        
        .mobile-mb-3 {
            margin-bottom: 1rem !important;
        }
    }
    
    /* Show only on mobile */
    .mobile-only {
        display: none !important;
    }
    
    @media (max-width: 768px) {
        .mobile-only {
            display: block !important;
        }
        
        .mobile-only-flex {
            display: flex !important;
        }
        
        .mobile-only-inline {
            display: inline !important;
        }
        
        .mobile-only-inline-block {
            display: inline-block !important;
        }
    }
    
    /* Smooth scrolling for mobile */
    @media (max-width: 768px) {
        html {
            scroll-behavior: smooth;
        }
        
        body {
            -webkit-overflow-scrolling: touch;
        }
    }
    
    /* Fix for iOS Safari */
    @supports (-webkit-touch-callout: none) {
        .main-content {
            min-height: -webkit-fill-available;
        }
        
        .sidebar {
            height: -webkit-fill-available;
        }
    }
    
    /* ========== CKEDITOR MOBILE FIX ========== */
    /* Fix CKEditor z-index issues in modals */
    :root {
        --ck-z-default: 1070 !important;
        --ck-z-modal: 1070 !important;
    }
    
    .ck.ck-editor__main > .ck-editor__editable {
        min-height: 300px;
    }
    
    @media (max-width: 768px) {
        .ck.ck-editor__main > .ck-editor__editable {
            min-height: 250px;
            font-size: 16px !important;
        }
        
        .ck.ck-toolbar {
            flex-wrap: wrap !important;
        }
        
        .ck.ck-toolbar .ck-toolbar__items {
            flex-wrap: wrap !important;
        }
        
        /* Make CKEditor dialogs mobile friendly */
        .ck.ck-dialog-overlay {
            padding: 0.5rem !important;
        }
        
        .ck.ck-dialog {
            max-width: calc(100% - 1rem) !important;
        }
    }
    
    @media (max-width: 576px) {
        .ck.ck-editor__main > .ck-editor__editable {
            min-height: 200px;
        }
        
        .ck.ck-button,
        .ck.ck-button__label {
            font-size: 0.875rem !important;
        }
    }
</style>

<script>
// Universal Mobile Menu Toggle Script - Works with all button implementations
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    
    // Check if sidebar exists
    if (!sidebar) return;
    
    // Create overlay element if not exists
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
    }
    
    // Check if toggle button already exists (#sidebarToggle from existing pages)
    let toggleBtn = document.getElementById('sidebarToggle');
    
    // If no existing button, create a new one
    if (!toggleBtn) {
        toggleBtn = document.createElement('button');
        toggleBtn.id = 'sidebarToggle';
        toggleBtn.className = 'mobile-menu-toggle';
        toggleBtn.innerHTML = '<i class="bi bi-list"></i>';
        document.body.appendChild(toggleBtn);
    }
    
    let isAnimating = false;
    
    // Toggle sidebar function
    function toggleSidebar(e) {
        if (isAnimating) return;
        isAnimating = true;
        
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        // Prevent body scroll on mobile when sidebar is open
        if (window.innerWidth <= 992) {
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }
        
        // Update icon
        const icon = toggleBtn.querySelector('i');
        if (icon) {
            icon.className = sidebar.classList.contains('active') ? 'bi bi-x-lg' : 'bi bi-list';
        }
        
        setTimeout(() => {
            isAnimating = false;
        }, 350);
    }
    
    // Close sidebar function
    function closeSidebar() {
        if (sidebar.classList.contains('active') && window.innerWidth <= 992) {
            toggleSidebar();
        }
    }
    
    // Event listeners for toggle button
    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        toggleSidebar(e);
    });
    
    // Close when clicking overlay
    overlay.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('active')) {
            toggleSidebar(e);
        }
    });
    
    // Prevent sidebar clicks from closing
    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close sidebar when clicking a menu item on mobile
    const menuLinks = document.querySelectorAll('.sidebar-menu a, .logout-section a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992 && sidebar.classList.contains('active')) {
                setTimeout(closeSidebar, 200);
            }
        });
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                const icon = toggleBtn.querySelector('i');
                if (icon) icon.className = 'bi bi-list';
            }
        }, 250);
    });
    
    // Keyboard navigation (ESC to close)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });
});
</script>
