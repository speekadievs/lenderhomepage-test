<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Traits\DataTableTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerController extends Controller
{
    use DataTableTrait;

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
        $teamId = $request->get('team_id', 0);

        $teams = $this->toTable($this->model->where('team_id', $teamId), ['first_name', 'last_name']);

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
