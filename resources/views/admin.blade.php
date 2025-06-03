<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-[#EDEDEC]">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-[#222222] text-black dark:text-[#EDEDEC] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded shadow">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="bg-red-500 text-white p-4 rounded shadow">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Users Table --}}
            <div class="bg-white dark:bg-[#272727] shadow rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-6">Manage Users</h3>

                <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-800">
                            <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-left">Name</th>
                            <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-left">Email</th>
                            <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">Admin</th>
                            <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-right">Balance ($)</th>
                            <th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $user->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $user->email }}</td>
                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-center">
                                @if ($user->is_admin)
                                    <span class="text-green-600 font-semibold">Yes</span>
                                @else
                                    <span class="text-red-600 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-right font-mono">
                                {{ number_format($user->balance, 2) }} $
                            </td>
                            <td class="border border-gray-300 dark:border-gray-700 px-12 py-2 text-center space-y-2">
                                {{-- Toggle Admin --}}
                                <form method="POST" action="{{ route('admin.toggleAdmin', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-1 rounded text-sm
                                        @if($user->id === auth()->id()) cursor-not-allowed opacity-50 @endif"
                                        @if($user->id === auth()->id()) disabled @endif>
                                        {{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}
                                    </button>
                                </form>

                                {{-- Reset Balance --}}
                                <form method="POST" action="{{ route('admin.resetBalance', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="w-full bg-red-500 hover:bg-red-600 text-white py-1 rounded text-sm">
                                        Reset Balance
                                    </button>
                                </form>

                                {{-- Update Balance --}}
                                <form method="POST" action="{{ route('admin.updateBalance', $user) }}" class="flex space-x-2 justify-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" step="1" min="0" name="balance" 
                                        value="{{ old('balance', $user->balance) }}" 
                                        class="w-40 text-right rounded border border-gray-300 dark:border-gray-600 px-2 py-1 text-sm
                                        bg-white dark:bg-[#444444] text-black dark:text-white"
                                        required />
                                    <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 rounded text-sm">
                                        Update
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
