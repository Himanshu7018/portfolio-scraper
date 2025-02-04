<x-app-layout>
    <style>
        .container{
            max-width: 1140px;
            margin: 0 auto;
        }
    </style>
    <div class="container mt-4">
        <h1 class="text-2xl font-bold mb-4">Add New Portfolio</h1>
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
            <div>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-black rounded-md shadow-sm text-black bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Portfolio
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
