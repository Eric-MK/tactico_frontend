<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About</title>
    <link rel="icon" href="{{ asset('scout.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-footer {
            margin-top: auto;
        }
        .about-section {
            padding: 50px 0;
            text-align: center;
        }
        .about-title {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .about-description {
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body class="main-body">
    @include('frontend.Navigation')

    <div class="container about-section">
        <h1 class="about-title">About Our System</h1>
        <p class="about-description">
            Our football recommender system is a cutting-edge tool designed to assist coaches and managers in scouting and evaluating players. With access to our comprehensive dataset, users can input a specific player, and our system responds by providing a list of players who exhibit similar qualities and attributes. Leveraging advanced algorithms and extensive player data, our system quickly analyzes player profiles and identifies individuals who closely resemble the inputted player in terms of playing style, position, skill set, and other relevant factors. By leveraging advanced algorithms and data analysis, our application facilitates quick and informed decision-making, enabling users to make well-informed choices when selecting players for their teams. Experience the power of our professional football recommender system and streamline your scouting process with confidence.
    </div>

    @include('frontend.FooterPage')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
