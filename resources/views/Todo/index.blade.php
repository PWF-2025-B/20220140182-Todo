<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Todo') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

        {{-- Header + Create Button + Flash Messages --}}
        <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <x-create-button href="{{ route('todo.create') }}" />
                </div>
                <div>
                    @if (session('success'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                           class="text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </p>
                    @endif

                    @if (session('danger'))
                        <p x-data="{ show: true }" x-show="show" x-transition
                           x-init="setTimeout(() => show = false, 5000)"
                           class="text-sm text-red-600 dark:text-red-400">
                            {{ session('danger') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Title</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($todos as $data)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <td scope="row" class="px-6 py-4 font-medium text-white dark:text-gray-900">
                                <a href="{{ route('todo.edit', $data) }}" class="hover:underline text-xs">
                                    {{ $data->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if (!$data->is_done)
                                    <span class="inline-flex items-center bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">
                                        On Going
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">
                                        Done
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                {{-- Edit Button --}}
                                <a href="{{ route('todo.edit', $data) }}" class="text-blue-500 hover:underline text-xs">
                                    Edit
                                </a>

                                {{-- Complete / Uncomplete Button --}}
                                @if (!$data->is_done)
                                    <form action="{{ route('todo.complete', $data) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 dark:text-green-400 text-xs hover:underline">
                                            Complete
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('todo.uncomplete', $data) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-blue-600 dark:text-blue-400 text-xs hover:underline">
                                            Uncomplete
                                        </button>
                                    </form>
                                @endif

                                {{-- Delete Button --}}
                                <form action="{{ route('todo.destroy', $data) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 text-xs hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Delete All Completed --}}
        @if ($todosCompleted > 1)
            <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
                <form action="{{ route('todo.deleteallcompleted') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-primary-button>
                        Delete All Completed Task
                    </x-primary-button>
                </form>
            </div>
        @endif

    </div>
</x-app-layout>
