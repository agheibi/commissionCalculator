<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Index</title>

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    </head>
    <body>
        <div class="upload__form">
            <form action="{{route('uploadFile')}}" method="POST" enctype="multipart/form-data">
                @csrf
            <input type="file" name="csv" id="csv" required>
            <input type="submit" value="submit" id="submit">
            </form>
        </div>
    </body>
</html>
