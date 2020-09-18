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
            <p>Date: {{$project->updated_at}}</p>

            @can('update', $project)
                <a href="{{route('projects.edit', ['project' => $project->id])}}">
                    <button type="button">Edit</button>
                </a>
            @endcan

            @auth
                <a href="{{route('donations.create', ["project" => $project->id])}}">
                    <button type="button">Donate</button>
                </a>
            @endauth

        </div>
    @endisset($project)
</div>
</body>
</html>

