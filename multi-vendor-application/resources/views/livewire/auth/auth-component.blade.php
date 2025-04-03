<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <!-- Heading -->
    <h2 class="text-2xl font-bold text-center mb-6">
        @if ($showOtpForm)
            Verify Your Email
        @else
            Login / Register
        @endif
    </h2>

    <!-- Form -->
    @if ($showOtpForm)
        <form wire:submit.prevent="verifyOtp" class="space-y-4">
            <!-- OTP Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700">OTP</label>
                <input
                    type="text"
                    wire:model="otp"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter the OTP from your email"
                >
                @error('otp') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Verify Button -->
            <button
                type="submit"
                class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Verify OTP
            </button>
        </form>
    @else
        <form wire:submit.prevent="submit" class="space-y-4">
            <!-- Email Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    wire:model="email"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter your email"
                >
                @error('email') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    wire:model="password"
                    required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter your password"
                >
                @error('password') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Login / Register
            </button>
        </form>
    @endif

    <!-- Message -->
    @if ($message)
        <p class="mt-4 text-center text-sm {{ str_contains($message, 'success') ? 'text-green-600' : 'text-red-600' }}">
            {{ $message }}
        </p>
    @endif
</div>
