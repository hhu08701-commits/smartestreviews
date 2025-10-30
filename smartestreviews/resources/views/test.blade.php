<!DOCTYPE html>
<html>
<head>
    <title>{{ $post->title }}</title>
</head>
<body>
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->excerpt }}</p>
    <p>Published: {{ $post->published_at->format('Y-m-d H:i:s') }}</p>
</body>
</html>
