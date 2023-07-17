<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HomePage</title>
    <link rel="icon" href="{{ asset('scout.png') }}" type="image/x-icon">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .main-body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-footer {
            margin-top: auto;
        }
    </style>
</head>

<body class="main-body">
    @include('frontend.Navigation') <!-- Include the navigation view -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <br><br><br>
                <img src="statistics.jpg" alt="Your Image" class="img-fluid" style="border-radius: 9px">
            </div>
            <div class="col-md-6">
                <form method="POST" action="{{ action('App\Http\Controllers\PlayerRecommendationController@index') }}" onsubmit="setPlayerType()">
                    @csrf
                    <div class="mb-3">
                        <label for="player_type" class="form-label">Player Type:</label>
                        <select class="form-control" id="player_type" name="player_type">
                            <option>Outfield players</option>
                            <option>Goalkeepers</option>
                        </select>
                    </div>
                    <input type="hidden" id="hidden_player_type" name="hidden_player_type">

                    <div class="mb-3">
                        <label for="query" class="form-label">Player Name:</label>
                        <input type="text" class="form-control" id="query" name="query"
                            placeholder="Firstname Secondname(Team)">
                    </div>
                    <div class="mb-3">
                        <label for="count" class="form-label">Count:</label>
                        <input type="text" class="form-control" id="count" name="count">
                    </div>
                    <div class="mb-3">
                        <label for="comparison" class="form-label">Comparison:</label>
                        <input type="text" class="form-control" id="comparison" name="comparison"
                            value="All positions" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="league" class="form-label">League:</label>
                        <select class="form-control" id="league" name="league">
                            <option>All</option>
                            <option>Premier League</option>
                            <option>La Liga</option>
                            <option>Bundesliga</option>
                            <option>Serie A</option>
                            <option>Ligue 1</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="setPlayerType()">Submit</button>
                </form>

            </div>
        </div> <!-- End of the row -->

        <!-- New row for the graph -->
        <div class="row mt-5">
            <div class="col-md-12">
                @if (isset($error))
                <script>
                    swal("Error", "{{ $error }}", "error");
                </script>
                @else
                @isset($data)
                <canvas id="similarityChart"></canvas>
                @endif
                @if (isset($data) && count($data) > 0)
                <script>
                    swal("Success", "Data loaded successfully", "success");
                </script>
                @endif
                @endif
            </div>
        </div> <!-- End of the row -->

        <!-- New row for the data -->
        <div class="row mt-5">
            <div class="col-md-12">
                @isset($data)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Competition</th>
                            <th>Age</th>
                            <th>Completed Matches Played</th>
                            <th>Player Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $player)
                <tr>
                    <td>{{ $player['Player'] }}</td>
                    <td>{{ $player['Pos'] }}</td>
                    <td>{{ $player['Comp'] }}</td>
                    <td>{{ $player['Age'] }}</td>
                    <td>{{ $player['90s'] }}</td>
                    <td>{{ $player_type }}</td>
                    <td>
                        <form action="{{ route('shortlist.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="player_name" value="{{ $player['Player'] }}">
                            <input type="hidden" name="position" value="{{ $player['Pos'] }}">
                            <input type="hidden" name="competition" value="{{ $player['Comp'] }}">
                            <input type="hidden" name="age" value="{{ $player['Age'] }}">
                            <input type="hidden" name="player_type" value="{{ $player_type }}">
                            <button type="submit" class="btn btn-primary">Add to Shortlist</button>
                        </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endisset
            </div>
        </div> <!-- End of the row -->
    </div>

    @include('frontend.FooterPage') <!-- Include the footer view -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Add the script to create the chart -->
    <script>

function setPlayerType() {
    var playerType = document.getElementById('player_type').value;
    document.getElementById('hidden_player_type').value = playerType;
}


        document.addEventListener('DOMContentLoaded', function () {
            @isset($data)
            const data = @json($data);
            const labels = data.map(player => player.Player);
            const similarities = data.map(player => parseFloat(player.Similarity));

            // get the minimum similarity value and decrease it a bit for the chart's minimum y value
            const minSimilarity = Math.min(...similarities) - 0.1;

            // get the maximum similarity value and increase it a bit for the chart's maximum y value
            const maxSimilarity = Math.max(...similarities) + 0.1;

            const ctx = document.getElementById('similarityChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Similarity',
                        data: similarities,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            min: minSimilarity,
                            max: maxSimilarity
                        }
                    }
                }
            });
            @endisset
        });


    </script>

</body>

</html>
