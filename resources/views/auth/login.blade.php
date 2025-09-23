<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #8e2de2, #ff6a00, #ff9a9e);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        .card-login {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            color: #fff;
            transition: all 0.3s ease-in-out;
        }

        .card-login:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 40px 12px 15px;
        }

        .form-control:focus {
            border-color: #6dd5fa;
            box-shadow: 0 0 10px rgba(109, 213, 250, 0.5);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #6dd5fa;
            font-size: 18px;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #6dd5fa;
            font-size: 18px;
        }

        .btn-custom {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #2a5298, #6dd5fa);
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(109, 213, 250, 0.4);
        }

        .text-muted {
            color: #e0e0e0 !important;
        }
    </style>

    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card-login shadow-lg border-0" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <h2 class="fw-bold">ENTRY PDRB</h2>
                <p class="text-muted">Masukan username email BPS anda untuk login</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4 position-relative">
                    <i class="bi bi-envelope input-icon"></i>
                    <input
                        type="email"
                        class="form-control ps-5 @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Email"
                        required autofocus autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>

                <!-- Password -->
                <div class="mb-4 position-relative">
                    <i class="bi bi-lock input-icon"></i>
                    <input
                        type="password"
                        id="password"
                        class="form-control ps-5 @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="Password"
                        required autocomplete="current-password">
                    <span class="password-toggle" onclick="togglePassword()">
                        <i id="toggleIcon" class="bi bi-eye"></i>
                    </span>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>

                <!-- Submit -->
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-custom text-white">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Toggle Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        }
    </script>

    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</x-guest-layout>