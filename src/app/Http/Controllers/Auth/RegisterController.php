<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
|--------------------------------------------------------------------------
| Register Controller
|--------------------------------------------------------------------------
|
| This controller handles the registration of new users as well as their
| validation and creation. By default this controller uses a trait to
| provide this functionality without requiring any additional code.
|
*/

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return ValidatorContract
     */
    protected function validator(array $data): ValidatorContract
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(Request $request): JsonResponse
    {
        $this->validator($request->all())->validate();

        $this->create($request->all());

        $token = auth()->attempt([
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        return response()->json([
            'success' => true,
            'token'   => $token,
            'expires' => auth()->factory()->getTTL(),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     * @throws Exception
     */
    protected function create(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
