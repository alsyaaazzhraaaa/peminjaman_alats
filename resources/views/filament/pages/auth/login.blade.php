 <div class="elegant-login-wrapper">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        .elegant-login-wrapper {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            background-color: #0f111a;
            overflow: hidden;
            z-index: 100;
        }

        /* Abstract Background Elements */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            z-index: -1;
            animation: floatShape 10s infinite alternate ease-in-out;
        }

        .bg-shape-1 {
            background: linear-gradient(135deg, #4f46e5, #ec4899);
            width: 400px;
            height: 400px;
            top: -10%;
            left: -10%;
        }

        .bg-shape-2 {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            width: 500px;
            height: 500px;
            bottom: -20%;
            right: -10%;
            animation-duration: 12s;
            animation-direction: alternate-reverse;
        }

        .bg-shape-3 {
            background: linear-gradient(135deg, #8b5cf6, #c026d3);
            width: 300px;
            height: 300px;
            top: 40%;
            left: 30%;
            animation-duration: 15s;
            opacity: 0.3;
        }

        @keyframes floatShape {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-30px) scale(1.1); }
        }

        .login-glass-card {
            width: 100%;
            max-width: 420px;
            background: rgba(20, 24, 39, 0.45);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUpFade {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Header area */
        .brand-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.02));
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 8px 16px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-icon svg {
            width: 32px;
            height: 32px;
            color: #60a5fa;
            filter: drop-shadow(0 2px 4px rgba(96, 165, 250, 0.4));
        }

        .card-title {
            text-align: center;
            color: #ffffff;
            font-size: 26px;
            font-weight: 600;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .card-subtitle {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            margin: 0 0 32px;
            font-weight: 300;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #cbd5e1;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
            transition: color 0.3s;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper svg {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #64748b;
            transition: color 0.3s;
        }

        .glass-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            font-family: inherit;
            font-size: 15px;
            padding: 14px 16px 14px 44px;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        .glass-input::placeholder {
            color: #475569;
        }

        .glass-input:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15), inset 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .glass-input:focus + svg,
        .input-wrapper:focus-within svg {
            color: #60a5fa;
        }

        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 32px;
            user-select: none;
        }

        .custom-checkbox {
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(15, 23, 42, 0.5);
            outline: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            margin: 0;
            display: grid;
            place-content: center;
        }

        .custom-checkbox::before {
            content: "";
            width: 10px;
            height: 10px;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
            transform: scale(0);
            background-color: white;
            transition: 120ms transform ease-in-out;
        }

        .custom-checkbox:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .custom-checkbox:checked::before {
            transform: scale(1);
        }

        .checkbox-group label {
            margin-left: 10px;
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
        }

        /* Buttons */
        .submit-btn {
            width: 100%;
            background: linear-gradient(to right, #3b82f6, #6366f1);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(59, 130, 246, 0.5);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Error state */
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        .error-message svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        /* Loading spinner */
        .spinner {
            animation: spin 1s linear infinite;
            width: 18px;
            height: 18px;
            margin-right: 8px;
            display: none;
        }

        .submitting .spinner {
            display: block;
        }
        
        .submitting .btn-text {
            display: none;
        }
        
        .loading-text {
            display: none;
            letter-spacing: 1px;
        }
        
        .submitting .loading-text {
            display: inline;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .footer {
            margin-top: 32px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }
    </style>

    <!-- Background Elements -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>

    <!-- Login Context -->
    <div class="login-glass-card">
        <div class="brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        
        <h1 class="card-title">Selamat Datang</h1>
        <p class="card-subtitle">Sistem Manajemen Peminjaman Alat</p>

        <form wire:submit.prevent="authenticate">
            @if($errors->has('data.username') || $errors->has('data.password'))
            <div class="error-message">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Kredensial yang dimasukkan tidak cocok dengan catatan kami.</span>
            </div>
            @endif

            <div class="form-group">
                <label for="username">Nama Pengguna</label>
                <div class="input-wrapper">
                    <input wire:model="data.username" type="text" id="username" class="glass-input" placeholder="Masukkan username Anda" required autofocus autocomplete="username">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <input wire:model="data.password" type="password" id="password" class="glass-input" placeholder="••••••••" required autocomplete="current-password">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <div class="checkbox-group">
                <input wire:model="data.remember" type="checkbox" id="remember" class="custom-checkbox">
                <label for="remember">Ingat Sesi Saya</label>
            </div>

            <button type="submit" class="submit-btn" wire:loading.class="submitting" wire:target="authenticate">
                <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="btn-text">MASUK KE PORTAL</span>
                <span class="loading-text">MEMPROSES...</span>
            </button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} Sistem Peminjaman Alat
        </div>
    </div>
</div>
