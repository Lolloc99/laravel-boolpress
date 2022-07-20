<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Un nuovo post è stato creato!</h1>
    <h3>Titolo: {{ $new_post->title }} </h3>
    <a href="{{ route('admin.posts.show', ['post' => $new_post->id]) }}">Leggi il post</a>
</body>
</html>