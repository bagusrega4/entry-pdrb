<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Ditolak</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #f7f6f3;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #111827;
        }

        .box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 40px 32px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f97316;
        }

        .code {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #9ca3af;
            margin-bottom: 6px;
        }

        h1 { font-size: 20px; font-weight: 700; margin-bottom: 8px; }

        p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            height: 38px;
            padding: 0 18px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            transition: background .12s;
        }

        .btn-primary { color: #fff; background: #f97316; border: 1px solid #ea580c; }
        .btn-primary:hover { background: #ea580c; }

        .btn-light { color: #374151; background: #f9fafb; border: 1px solid #e5e7eb; }
        .btn-light:hover { background: #f3f4f6; }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        <div class="code">ERROR 403</div>
        <h1>Akses Ditolak</h1>
        <p>
            Anda tidak memiliki izin untuk membuka halaman ini.
            Jika Ada masalah, hubungi Admin.
        </p>

        <div class="actions">
            <a href="{{ route('dashboard.index') }}" class="btn btn-primary">Ke Dashboard</a>
            <a href="javascript:history.back()" class="btn btn-light">Kembali</a>
        </div>
    </div>
</body>
</html>