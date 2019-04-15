<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Traits\DataTableTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeamController extends Controller
{
    use DataTableTrait;

    /**
     * @var Team
     */
    private $model;

    /**
     * TeamController constructor.
     */
    public function __construct()
    {
        $this->model = app(Team::class);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $teams = $this->toTable($this->model->withCount('players'));

        return TeamResource::collection($teams);
    }

    /**
     * @param Team $team
     * @return TeamResource
     */
    public function show(Team $team): TeamResource
    {
        $team->load('players');

        return new TeamResource($team);
    }

    /**
     * @param StoreTeamRequest $request
     * @param Team|null $team
     * @return TeamResource
     */
    public function store(StoreTeamRequest $request, Team $team = null): TeamResource
    {
        if ($team) {
            $team->update($request->all());
        } else {
            $team = $this->model->create($request->all());
        }

        return new TeamResource($team);
    }

    /**
     * @param StoreTeamRequest $request
     * @param Team $team
     * @return TeamResource
     */
    public function update(StoreTeamRequest $request, Team $team): TeamResource
    {
        return $this->store($request, $team);
    }

    /**
     * @param Team $team
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Team $team): JsonResponse
    {
        $team->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
