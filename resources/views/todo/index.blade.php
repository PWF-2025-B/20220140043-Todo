<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Todo') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg p-6">
            {{-- Header Button + Alert --}}
            <div class="flex items-center justify-between mb-4">
                <x-create-button href="{{ route('todo.create') }}" />
                <div>
                    @if (session('success'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                            x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}
                        </p>
                    @endif
                    @if (session('danger'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                            x-init="setTimeout(() => show = false, 5000)"
                            class="text-sm text-red-600 dark:text-red-400">{{ session('danger') }}
                        </p>
                    @endif
                </div>
            </div>
            {{-- Table --}}
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Category</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todos as $data)
                            <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('todo.edit', $data) }}" class="hover:underline">{{ $data->title }}</a>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($data->category)
                                        {{ $data->category->title }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if (!$data->is_done)
                                        <span class="inline-flex items-center gap-1 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-900 text-xs font-semibold px-3 py-1 rounded-full dark:from-blue-800 dark:to-blue-900 dark:text-blue-200 shadow-sm">
                                            <svg class="w-3.5 h-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                            </svg>
                                            Ongoing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-900 text-xs font-semibold px-3 py-1 rounded-full dark:bg-green-800 dark:text-green-200 shadow-sm">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L9 14.414l-3.707-3.707a1 1 0 111.414-1.414L9 11.586l6.293-6.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Done
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-6">
                                        @if ($data->is_done == false)
                                            <form action="{{ route('todo.complete', $data) }}" method="Post">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 dark:text-green-400 hover:underline">
                                                    Complete
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('todo.uncomplete', $data) }}" method="Post">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    Uncomplete
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('todo.destroy', $data) }}" method="Post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="odd:bg-white dark:bg-gray-900 even:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($todoCompleted > 1)
                <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
                    <form action="{{ route('todo.deleteallcompleted') }}" method="Post">
                        @csrf
                        @method('delete')
                        <x-primary-button>
                            Delete All Completed Task
                        </x-primary-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>