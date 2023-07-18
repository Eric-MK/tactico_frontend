<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
       .navbar {
            transition: box-shadow 0.3s ease-in-out;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .navbar:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .nav-link {
            text-decoration: none;
            transition: transform 0.3s ease-in-out;
        }

        .nav-link:hover {
            text-decoration: none;
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            border-radius: 9px;
            margin-left:10px;
            margin-right:5px;

        }

        .nav-link.active {
            border: 1px solid black;
            border-radius: 9px;
            text-decoration: none;
            background: linear-gradient(to right, #aaa, #ddd);
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

        .nav-link.active:hover {
            text-decoration: none;
            background: linear-gradient(to right, #ddd, #aaa);
        }

        .nav-link.logout{
           color: red;
           text-decoration: none;
        }

        .nav-link.logout:hover{
            color: white;
            font-size: 15px;
            text-decoration: none;
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(255,0,0,0.5);
            border-radius: 9px;
            margin-left: 10px;
            background-color: rgb(243, 67, 67);

        }

    </style>
</head>
<body>
<!-- Your content here -->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">Tactico</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">Home_Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shortlisted-players') ? 'active' : '' }}" href="{{ url('shortlisted-players', Auth::user()->id) }}">Shortlist</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pro') ? 'active' : '' }}" href="/pro">Profile</a>
                </li>
            </ul>

            <span class="navbar-text">
                Logged in as: {{ auth()->user()->name }}
            </span>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link logout" href="{{ route('logout') }}">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
