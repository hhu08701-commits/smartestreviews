<!DOCTYPE html>
<html>
<head>
    <title>{{ $post->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
        <p class="text-gray-600 mb-6">{{ $post->excerpt }}</p>
        <div class="prose max-w-none">
            {!! $post->content !!}
        </div>
    </div>
</body>
</html>
