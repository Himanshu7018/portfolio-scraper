<!-- resources/views/admin/dashboard.blade.php -->
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body> -->
<x-app-layout>    
    <!-- <h1>Welcome to Admin Dashboard</h1> -->
<section class="max-w-6xl mx-auto mt-4 text-center" style="padding-bottom: 40px;">
    <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="csv_file">Upload CSV File:</label>
        <input type="file" name="csv_file" id="csv_file" required>
        <button type="submit">Upload</button>
    </form>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
</section>    
</x-app-layout>    
<!-- </body>
</html> -->
