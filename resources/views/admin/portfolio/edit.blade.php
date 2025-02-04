<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Portfolio') }}
        </h2>
    </x-slot>

    <!-- <body> -->
        <!-- <div class="container mx-auto p-6"> -->
            <form action="{{ route('admin.portfolio.update', $portfolio->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="title" name="title" value="{{ $portfolio->title }}" required
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $portfolio->description }}</textarea>
                    </div>

                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                        <input type="url" id="url" name="url" value="{{ $portfolio->url }}"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="technology" class="block text-sm font-medium text-gray-700">Technology</label>
                        <input type="text" id="technology" name="technology" value="{{ $portfolio->technology }}"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="service_type" class="block text-sm font-medium text-gray-700">Service Type</label>
                        <input type="text" id="service_type" name="service_type" value="{{ $portfolio->service_type }}"
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" required
                            class="mt-1 p-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Active" {{ $portfolio->status === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $portfolio->status === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="btn btn-primary bg-blue-500 border border-black text-black px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Update Portfolio
                        </button>
                    </div>
                </div>
            </form>
        <!-- </div> -->
    <!-- </body> -->
</x-guest-layout>
