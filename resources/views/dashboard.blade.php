<h1>Welcome... {{ auth()->user()->name }}</h1>

<form method="POST" action="{{ action('App\Http\Controllers\PlayerRecommendationController@index') }}">
    @csrf
    <label for="player_type">Player Type:</label>
    <input type="text" id="player_type" name="player_type"><br>

    <label for="query">Player Name:</label>
    <input type="text" id="query" name="query"><br>

    <label for="count">Count:</label>
    <input type="text" id="count" name="count"><br>

    <label for="comparison">Comparison:</label>
    <input type="text" id="comparison" name="comparison"><br>

    <label for="league">League:</label>
    <input type="text" id="league" name="league"><br>

    <input type="submit" value="Submit">
</form>

@if (isset($error))
    <p>Error: {{ $error }}</p>
@else
    @isset($data)
    @foreach ($data as $player)
    <p>Name: {{ $player['Player'] }}</p>
    <p>Similarity: {{ $player['Similarity'] }}</p>
    <p>Stats: </p>
    <ul>
        <li>1: {{ $player['1'] }}</li>
        <li>11: {{ $player['11'] }}</li>
        <li>141: {{ $player['141'] }}</li>
        <li>3: {{ $player['3'] }}</li>
        <li>5: {{ $player['5'] }}</li>
        <li>6: {{ $player['6'] }}</li>
    </ul>
    <br>
@endforeach
    @endisset
@endif

<a href="{{ route('logout') }}">Logout</a>
