<x-app-layout>    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bulk Upload') }}
        </h2>
    </x-slot>

    <section class="max-w-6xl mx-auto mt-4 text-center" style="padding-bottom: 40px;">
        <form action="{{ route('admin.upload') }}" method="POST" class="dropzone" id="csvDropzone" enctype="multipart/form-data">
            @csrf
        </form>
        
        @if (session('success'))
            <p class="text-green-500 mt-4">{{ session('success') }}</p>
        @endif
    </section>    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" />

    <script>
        Dropzone.options.csvDropzone = {
            paramName: 'csv_file',
            maxFiles: 1,
            acceptedFiles: '.csv',
            dictDefaultMessage: "Drag & drop your CSV file here or click to upload",
            init: function () {
                this.on("success", function (file, response) {
                    alert("Upload successful!");
                });
                this.on("error", function (file, response) {
                    alert("Upload failed: " + response);
                });
            }
        };
    </script>
</x-app-layout>
