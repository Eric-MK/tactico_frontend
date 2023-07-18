<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class PlayerRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $client = new Client(['base_uri' => 'http://127.0.0.1:5000/recommend']);

        $player_type = $request->input('player_type');
        $query = $request->input('query');
        $count = $request->input('count');
        $comparison = $request->input('comparison');
        $league = $request->input('league');

        try {
            $response = $client->request('POST', '/recommend', [
                'json' => [
                    'player_type' => $player_type,
                    'query' => $query,
                    'count' => $count,
                    'comparison' => $comparison,
                    'league' => $league,
                ]
            ]);
        } catch (RequestException $e) {
            // You can log the error or handle it differently here
            return view('dashboard', ['error' => 'Unable to fetch data from the API: ' . $e->getMessage()]);
        }

        if ($response->getStatusCode() != 200) {
            // API returned a non-success status code
            return view('dashboard', ['error' => 'API request failed with status code ' . $response->getStatusCode()]);
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (array_key_exists('error', $data)) {
            // API returned an error message
            return view('dashboard', ['error' => 'API error: ' . $data['error']]);
        }

        return view('dashboard', compact('data', 'player_type'));
    }
}
