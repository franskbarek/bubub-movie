<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth_ui.sign_in') }} — Bubub Movie</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --red: #E50914; --bg: #141414; --text: #FFFFFF; --muted: #B3B3B3; }
        html, body { min-height: 100vh; background: var(--bg); font-family: 'Inter', sans-serif; color: var(--text); }

        .login-page {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .bg-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0a0a 50%, #0a0a0a 100%);
        }
        .bg-grid {
            position: absolute; inset: 0;
            background-image: linear-gradient(rgba(229,9,20,0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(229,9,20,0.05) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Language bar ── */
        .lang-bar {
            position: fixed; top: 20px; right: 20px;
            display: flex; gap: 6px; z-index: 100;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(10px);
            padding: 6px 8px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);
        }
        .lang-bar a {
            padding: 5px 12px; border-radius: 5px; font-size: 0.78rem; font-weight: 700;
            color: var(--muted); text-decoration: none; border: 1px solid transparent;
            transition: all 0.2s; letter-spacing: 0.5px;
        }
        .lang-bar a.active { color: var(--text); background: rgba(229,9,20,0.2); border-color: var(--red); }
        .lang-bar a:hover:not(.active) { color: var(--text); border-color: rgba(255,255,255,0.2); }

        /* ── Card ── */
        .login-card {
            position: relative; z-index: 10;
            background: rgba(20,20,20,0.95); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px; padding: 48px 40px; width: 100%; max-width: 440px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.8);
            animation: cardIn 0.4s ease;
        }
        @keyframes cardIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

        .login-logo { text-align: center; margin-bottom: 32px; }
        .logo-text { font-size: 2.2rem; font-weight: 900; color: var(--red); letter-spacing: -1px; }
        .logo-text span { color: var(--text); }
        .logo-tagline { font-size: 0.78rem; color: var(--muted); margin-top: 4px; letter-spacing: 2px; text-transform: uppercase; }

        .login-title { font-size: 1.6rem; font-weight: 800; margin-bottom: 6px; text-align: center; }
        .login-subtitle { font-size: 0.9rem; color: var(--muted); margin-bottom: 28px; text-align: center; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--muted); margin-bottom: 7px; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-group { position: relative; }
        .form-input {
            width: 100%; background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12); border-radius: 8px;
            color: var(--text); padding: 14px 16px 14px 42px; font-size: 0.95rem;
            font-family: inherit; outline: none; transition: all 0.2s;
        }
        .form-input:focus { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.35); }
        .form-input.error-input { border-color: var(--red); }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.3); font-size: 0.9rem; pointer-events: none; }
        .toggle-password {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.35);
            font-size: 0.9rem; transition: color 0.2s; padding: 4px;
        }
        .toggle-password:hover { color: white; }
        .password-input { padding-right: 44px !important; }

        .error-msg {
            background: rgba(229,9,20,0.1); border: 1px solid rgba(229,9,20,0.3);
            border-radius: 8px; padding: 12px 16px; margin-bottom: 18px;
            font-size: 0.85rem; color: #ff6b6b;
            display: flex; align-items: center; gap: 8px;
            animation: shake 0.4s ease;
        }
        @keyframes shake {
            0%,100%{transform:translateX(0)}
            20%{transform:translateX(-6px)}
            40%{transform:translateX(6px)}
            60%{transform:translateX(-4px)}
            80%{transform:translateX(4px)}
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%; padding: 15px; background: var(--red);
            border: none; border-radius: 8px; color: white; font-size: 1rem;
            font-weight: 700; cursor: pointer; transition: all 0.2s;
            font-family: inherit; margin-top: 8px; position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            min-height: 52px;
        }
        .btn-login:hover:not(:disabled) { background: #cc0812; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(229,9,20,0.4); }
        .btn-login:active:not(:disabled) { transform: translateY(0); }
        .btn-login:disabled { opacity: 0.8; cursor: not-allowed; transform: none; }

        /* ── Spinner inside button ── */
        .btn-spinner {
            width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white; border-radius: 50%;
            animation: spin 0.7s linear infinite;
            flex-shrink: 0;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Loading overlay ── */
        .loading-overlay {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
            align-items: center; justify-content: center; flex-direction: column; gap: 16px;
        }
        .loading-overlay.show { display: flex; }
        .loading-overlay .big-spinner {
            width: 48px; height: 48px;
            border: 3px solid rgba(255,255,255,0.15);
            border-top-color: var(--red); border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        .loading-overlay p { color: rgba(255,255,255,0.7); font-size: 0.9rem; letter-spacing: 0.5px; }

        /* ── Progress bar ── */
        .progress-bar {
            position: fixed; top: 0; left: 0; height: 3px;
            background: var(--red); width: 0%; z-index: 9999;
            transition: width 0.3s ease; box-shadow: 0 0 8px var(--red);
        }

        .demo-hint {
            margin-top: 24px; padding: 14px 16px; background: rgba(255,255,255,0.04);
            border-radius: 8px; font-size: 0.82rem; color: var(--muted);
            border: 1px solid rgba(255,255,255,0.06);
        }
        .demo-hint strong { color: var(--text); }
    </style>
</head>
<body>

    <!-- Progress bar -->
    <div class="progress-bar" id="progressBar"></div>

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="big-spinner"></div>
        <p>{{ __('auth_ui.signing_in') }}</p>
    </div>

    <!-- Language bar -->
    <div class="lang-bar">
        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
        <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
    </div>

    <div class="login-page">
        <div class="bg-overlay"></div>
        <div class="bg-grid"></div>

        <div class="login-card">
            <div class="login-logo">
                <div class="logo-text">BUBUB<span>MOVIE</span></div>
                <div class="logo-tagline">{{ __('auth_ui.tagline') }}</div>
            </div>

            <h1 class="login-title">{{ __('auth_ui.welcome_back') }}</h1>
            <p class="login-subtitle">{{ __('auth_ui.sign_in_to_continue') }}</p>

            @if($errors->any())
            <div class="error-msg">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form id="loginForm" action="{{ route('login') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label class="form-label" for="username">{{ __('auth_ui.username') }}</label>
                    <div class="input-group">
                        <i class="fas fa-user input-icon"></i>
                        <input
                            type="text" id="username" name="username"
                            class="form-input @error('username') error-input @enderror"
                            value="{{ old('username') }}"
                            placeholder="{{ __('auth_ui.username_placeholder') }}"
                            autocomplete="username" autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">{{ __('auth_ui.password') }}</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input
                            type="password" id="password" name="password"
                            class="form-input password-input @error('password') error-input @enderror"
                            placeholder="{{ __('auth_ui.password_placeholder') }}"
                            autocomplete="current-password"
                        >
                        <button type="button" class="toggle-password" onclick="togglePass()" tabindex="-1">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">
                    <span id="btnText">{{ __('auth_ui.sign_in') }}</span>
                    <i class="fas fa-arrow-right" id="btnIcon"></i>
                </button>
            </form>

            <div class="demo-hint">
                <strong>{{ __('auth_ui.demo_credentials') }}:</strong><br>
                {{ __('auth_ui.username') }}: <strong>aldmic</strong> &nbsp;|&nbsp;
                {{ __('auth_ui.password') }}: <strong>123abc123</strong>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePass() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Loading animation on submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username || !password) return; // let browser/server validate

            const btn     = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const overlay = document.getElementById('loadingOverlay');
            const bar     = document.getElementById('progressBar');

            // Button loading state
            btn.disabled  = true;
            btnText.textContent = '{{ __("auth_ui.signing_in") }}';
            btnIcon.outerHTML = '<div class="btn-spinner"></div>';

            // Progress bar animation
            bar.style.width = '30%';
            setTimeout(() => bar.style.width = '60%', 300);
            setTimeout(() => bar.style.width = '85%', 700);

            // Overlay after short delay
            setTimeout(() => overlay.classList.add('show'), 400);
        });

        // If page has error (form resubmitted), reset button state
        @if($errors->any())
        // errors present — no loading state needed
        @endif
    </script>
</body>
</html>
