<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="form-error" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="your@email.com" />
            <x-input-error :messages="$errors->get('email')" class="form-error" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Enter a strong password" />
            <x-input-error :messages="$errors->get('password')" class="form-error" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="form-error" />
        </div>

        <!-- Terms Checkbox -->
        <div class="form-group">
            <label for="terms" class="checkbox-label">
                <input id="terms" type="checkbox" name="terms" required>
                <span>I agree to the <a href="#" style="color: var(--primary);">Terms of Service</a></span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-full mt-6">
            {{ __('Create Account') }}
        </button>
    </form>

    <!-- Login Link -->
    <div class="divider-text">
        <span>{{ __('or') }}</span>
    </div>

    <div class="auth-links justify-center">
        <span class="auth-link-text">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}">{{ __('Sign in here') }}</a>
        </span>
    </div>
</x-guest-layout>
