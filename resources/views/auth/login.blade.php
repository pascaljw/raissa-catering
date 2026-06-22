<x-guest-layout>
    @if(session('status'))
        <div class="mb-4">
            <x-auth-session-status class="inline-flex w-full rounded-xl border border-primary/20 bg-primary/10 p-4 text-sm text-primary" :status="session('status')" />
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email Address')" class="text-primary" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="your@email.com" />
            <x-input-error :messages="$errors->get('email')" class="form-error" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" class="text-primary" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="form-error" />
        </div>

        <!-- Remember Me -->
        <div>
            <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-primary">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-primary hover:text-primary-dark transition" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <button type="submit" class="btn btn-primary w-full sm:w-auto px-8 py-3 text-base">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>

    <!-- Register Link -->
    <div class="mt-8 border-t border-primary/20 pt-6 text-center text-sm text-slate-600">
        <p class="mb-2 text-primary font-semibold">{{ __('New here?') }}</p>
        <p>
            {{ __('Don\'t have an account?') }}
            <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-primary-dark transition">{{ __('Register now') }}</a>
        </p>
    </div>
</x-guest-layout>
