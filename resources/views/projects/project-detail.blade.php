<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

</head>
<body class="antialiased">
<div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 projectList">

    @isset($project)
            <div style="display: block; width:70%;color:white;">
                <h1>{{ $project->name }}</h1>
                <p>Description: <br> {{$project->description}} <br/></p>
                <p>Author: {{$project->user->name}}</p>
                <p>Date: {{$project->created_at}}</p>

                <a href="/products/{{$project->id}}">
                    <button type="button">Details</button>
                </a>
            </div>
    @endisset($project)
</div>
</body>
</html>

