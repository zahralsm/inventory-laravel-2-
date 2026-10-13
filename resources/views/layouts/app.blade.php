<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stockroom') — Inventory Management</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        :root {
            --sage: #7c9a83;
            --sage-dark: #587461;
            --mint: #edf5ef;
            --mint-light: #f6faf7;
            --white: #ffffff;
            --text: #34483a;
            --muted: #829087;
            --border: #e2ebe4;
            --danger: #b95c5c;
        }

        body {
            margin: 0;
            background: var(--mint-light);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
        }

        .container {
            width: min(1180px, 94%);
            margin: 28px auto 40px;
        }

        /* Modern table & card styling consistent with P4 */
        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(50, 75, 55, 0.04);
            margin-bottom: 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
        }

        .btn-primary {
            background: var(--sage);
            color: #ffffff;
        }
        .btn-primary:hover {
            background: var(--sage-dark);
        }

        .btn-secondary {
            background: #e2ebe4;
            color: var(--text);
        }
        .btn-secondary:hover {
            background: #d4e0d7;
        }

        .btn-danger {
            background: var(--danger);
            color: #ffffff;
        }
        .btn-danger:hover {
            background: #a34a4a;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 15px;
        }

        table.data-table th, table.data-table td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        table.data-table th {
            background: #fafcfa;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        table.data-table tr:hover {
            background: #fbfdfb;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #e8f5e9; color: #2e7d32; }
        .badge-warning { background: #fff8e1; color: #f57f17; }
        .badge-danger { background: #ffebee; color: #c62828; }
        .badge-info { background: #e3f2fd; color: #1565c0; }

        /* Form elements */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            font-size: 13px;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            background: #ffffff;
        }

        .form-control:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 3px rgba(124, 154, 131, 0.15);
        }

        /* Pagination simple styles */
        .pagination-container nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
    </style>
    @yield('styles')
</head>
<body class="@yield('body-class')">

    @auth
        @include('layouts.navigation')
    @endauth

    <main class="container">
        <x-alert />
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
