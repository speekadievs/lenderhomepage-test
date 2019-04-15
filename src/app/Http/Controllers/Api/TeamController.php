<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Request;

class TeamController extends Controller
{
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
        $count = $request->get('count');
        $query = $request->get('query');

        $teams = $this->model->when($query, function (Builder $builder) use ($query) {
            return $builder->where('name', 'like', '%' . $query . '%');
        })->withCount('players')->paginate($count);

        return TeamResource::collection($teams);
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
