<?php

namespace App\Http\Controllers;


use App\Http\Resources\GameResource;
use App\Http\Resources\MainResource;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->has('count')) {
            return MainResource::collection(Game::with('minPrices')
                ->get()
                ->reverse()
                ->take($request->get('count')));
        }

        //фильтры по названию
        elseif ($request->has('nameaz')) {
            return MainResource::collection(Game::with('minPrices')->orderBy('game')->get());
        } elseif ($request->has('nameza')) {
            return MainResource::collection(Game::with('minPrices')->orderBy('game')->get()->reverse());
        }

        //фильтры по цене
        elseif ($request->has('pricemin')) {
            return MainResource::collection(Game::select(['games.id', 'games.game', 'games.image', 'prices.price'])
                ->join('prices', 'games.id', '=', 'prices.idGame')->orderBy('price')->get()->unique('id'));
        } elseif ($request->has('pricemax')) {
            return MainResource::collection(Game::select(['games.id', 'games.game', 'games.image', 'prices.price'])
                ->join('prices', 'games.id', '=', 'prices.idGame')->orderBy('price')->get()->unique('id')->reverse());
        }

        //фильтр "бесплатно"
        elseif ($request->has('free')) {
            return MainResource::collection(Game::select(['games.id', 'games.game', 'games.image', 'prices.price'])
                ->join('prices', 'games.id', '=', 'prices.idGame')->where('price', '0')->orderBy('price')->get()->unique('id')->reverse());
        }

        //фильтры по добавлению
        elseif ($request->has('new')) {
            return MainResource::collection(Game::select(['games.id', 'games.game', 'games.image', 'prices.price', 'games.releaseDate'])
                ->join('prices', 'games.id', '=', 'prices.idGame')->orderBy('releaseDate')->get()->unique('id'))->reverse();
        } elseif ($request->has('old')) {
            return MainResource::collection(Game::select(['games.id', 'games.game', 'games.image', 'prices.price', 'games.releaseDate'])
                ->join('prices', 'games.id', '=', 'prices.idGame')->orderBy('releaseDate')->get()->unique('id'));
        }

        else {
            return MainResource::collection(Game::with('minPrices')->get());
        }
    }
//        Commentasdfasdfasdf

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return GameResource
     */
    public function show($id)
    {
        return new GameResource(Game::with('prices', 'genres', 'gallery')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}