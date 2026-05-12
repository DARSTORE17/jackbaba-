<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
    @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css');

    :root {
        --primary-color: {{ $systemColors['primary'] ?? '#4fbb89' }};
        --secondary-color: {{ $systemColors['secondary'] ?? '#96d6ab' }};
        --accent-color: var(--primary-color);
        --success-color: var(--primary-color);
        --warning-color: var(--secondary-color);
        --danger-color: var(--primary-color);
        --background-color: var(--secondary-color);
        --text-color: var(--primary-color);
        --blue-600: #2563eb;
        --blue-700: #1d4ed8;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-600: #475569;
        --slate-700: #334155;
        --slate-800: #1e293b;
        --slate-900: #0f172a;
        --white: #ffffff;
        --surface-color: rgba(255, 255, 255, 0.96);
        --surface-strong: rgba(255, 255, 255, 1);
        --shadow-soft: 0 20px 50px rgba(34, 77, 47, 0.08);
        --shadow-softest: 0 12px 30px rgba(34, 77, 47, 0.06);
        --radius-lg: 22px;
        --radius-md: 16px;
        --radius-sm: 12px;
        --font-sans: "Plus Jakarta Sans", Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    *, *::before, *::after {
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        margin: 0;
        color: var(--text-color);
        background: var(--background-color);
        font-family: var(--font-sans);
        font-weight: 500;
        letter-spacing: 0;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .navbar-brand,
    .card-title,
    .modal-title,
    .table thead th {
        font-family: var(--font-sans);
        font-weight: 800;
        letter-spacing: 0;
    }

    h1,
    .display-1,
    .display-2,
    .display-3,
    .display-4,
    .display-5,
    .display-6 {
        font-weight: 900;
    }

    a,
    button,
    input,
    select,
    textarea {
        font-family: inherit;
    }

    .card,
    .modal-content,
    .dropdown-menu,
    .form-control,
    .form-select,
    .btn,
    .list-group-item,
    .table {
        border-radius: var(--radius-md) !important;
        border: 1px solid rgba(15, 23, 42, 0.08) !important;
        background-color: var(--surface-color) !important;
        box-shadow: var(--shadow-softest) !important;
    }

    .card {
        font-weight: 600;
    }

    .btn,
    .badge,
    .nav-link,
    .dropdown-item,
    label,
    .form-label {
        font-weight: 700;
        letter-spacing: 0;
    }

    .btn {
        text-transform: none;
    }

    .table {
        font-weight: 600;
    }

    .btn-primary,
    .bg-primary {
        background-color: var(--primary-color) !important;
        border-color: transparent !important;
        box-shadow: 0 12px 24px rgba(79, 187, 137, 0.16);
    }

    .btn-outline-primary {
        border-color: rgba(79, 187, 137, 0.3) !important;
        color: var(--primary-color) !important;
    }

    .btn-outline-secondary {
        border-color: rgba(150, 214, 171, 0.3) !important;
        color: var(--secondary-color) !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-muted {
        color: var(--primary-color) !important;
    }

    .bg-secondary {
        background-color: var(--secondary-color) !important;
        border-color: transparent !important;
    }

    .bg-success {
        background-color: var(--success-color) !important;
        border-color: transparent !important;
    }

    .bg-warning {
        background-color: var(--warning-color) !important;
        border-color: transparent !important;
    }

    .bg-danger {
        background-color: var(--danger-color) !important;
        border-color: transparent !important;
    }

    footer {
        background: rgba(255, 255, 255, 0.96);
        border-color: rgba(15, 23, 42, 0.08);
    }
</style>
