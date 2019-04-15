<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerController extends Controller
{
    /**
     * @var Player
     */
    private $model;

    /**
     * PlayerController constructor.
     */
    public function __construct()
    {
        $this->model = app(Player::class);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $count = $request->get('count');
        $query = $request->get('query');

        $teams = $this->model->when($query, function (Builder $builder) use ($query) {
            return $builder->where('first_name', 'like', '%' . $query . '%')
                ->orWhere('last_name', 'like', '%' . $query . '%');
        })->paginate($count);

        return PlayerResource::collection($teams);
    }

    /**
     * @param Player $player
     * @return PlayerResource
     */
    public function show(Player $player): PlayerResource
    {
        return new PlayerResource($player);
    }

    /**
     * @param StorePlayerRequest $request
     * @param Player|null $player
     * @return PlayerResource
     */
    public function store(StorePlayerRequest $request, Player $player = null): PlayerResource
    {
        if ($player) {
            $player->update($request->all());
        } else {
            $player = $this->model->create($request->all());
        }

        return new PlayerResource($player);
    }

    /**
     * @param StorePlayerRequest $request
     * @param Player $player
     * @return PlayerResource
     */
    public function update(StorePlayerRequest $request, Player $player): PlayerResource
    {
        return $this->store($request, $player);
    }

    /**
     * @param Player $player
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Player $player): JsonResponse
    {
        $player->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
