<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-white">
            {{ __('Add Portfolio') }}
        </h2>
    </x-slot>
    <div class="container mt-4">
        <form action="{{ route('admin.portfolio.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="3" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="mb-4">
                <label for="url" class="block text-sm font-medium text-gray-700">Portfolio URL</label>
                <input type="url" id="url" name="url" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="url" class="block text-sm font-medium text-gray-700">Technology</label>
                <input type="text" id="technology" name="technology" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="url" class="block text-sm font-medium text-gray-700">Service Type</label>
                <input type="text" id="service_type" name="service_type" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex justify-content-center mt-4">
                <button type="submit"
                    class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Portfolio
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
