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
<div>
    @isset($project)
        <div>
            <h1>Project created</h1>

            <p>Project name: <br> {{$project->name}} <br/></p>
            <p>Description: <br> {{$project->description}} <br/></p>
            <p>Author: {{$project->user->name}}</p>
            <p>Date: {{$project->created_at}}</p>

            <a href="{{route('projects.index')}}">
                Go back to project list
            </a>
        </div>
    @endisset($project)
</div>
</body>
</html>

