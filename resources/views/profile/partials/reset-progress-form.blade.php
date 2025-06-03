<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-[#EDEDEC]">
            {{ __('Reset Progress') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-[#cfcfcf]">
            {{ __('Once your progress is reset, all of your game data will be permanently deleted. Please download any data or information that you wish to retain before resetting your progress.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-reset-progress')"
    >{{ __('Reset Progress') }}</x-danger-button>

    <x-modal name="confirm-reset-progress" :show="$errors->progressReset->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.reset-progress') }}" class="p-6">
            @csrf
            @method('patch')

            <h2 class="text-lg font-medium text-gray-900 dark:text-[#EDEDEC]">
                {{ __('Are you sure you want to reset your progress?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-[#cfcfcf]">
                {{ __('Once your progress is reset, all of your game data will be permanently deleted. Please enter your password to confirm you would like to permanently reset your progress.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->progressReset->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Reset Progress') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
