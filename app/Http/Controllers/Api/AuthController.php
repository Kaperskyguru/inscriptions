<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Role;
use App\Http\Resources\UserResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;






class AuthController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
       
        $v = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'organizationName' => 'required|string|max:255|unique:organizations,name',
            'organizationAddress' => 'required',
            'organizationCity' => 'required',
            'organizationZipcode' => 'required',
            'organizationPhone' => 'required',
            'organizationLocale' => 'required',
            'organizationTypeId' => 'required'
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
       
        $data = $request->toArray();
        $user_data = [
            'name' => titleCase($data['firstname']) . ' ' . titleCase($data['lastname']),
            'email' => $data['email'],
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make($data['password']),
            'remember_token' => Str::random(10),
        ];
        $organization_data = [
            'name' => $data['organizationName'],
            'address' => $data['organizationAddress'],
            'city' => $data['organizationCity'],
            'zipcode' => $data['organizationZipcode'],
            'country_id' => $data['organizationCountry'],
            'state_id' => $data['organizationState'],
            'phone' => $data['organizationPhone'],
            'locale' => $data['organizationLocale'],
            'organization_type_id' => $data['organizationTypeId']
        ];

        $role_public  = Role::where('name', 'public')->first();

        $user = User::create($user_data);

        $user->roles()->attach($role_public);

        $user->organization()->create($organization_data);

        $userData = new UserResource($user);

        $credentials = $request->only('email', 'password');

        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        //$user = $this->guard()->user();
        app()->setLocale($data['organizationLocale']);


        return $this->respondWithToken($userData,$token);
      
    }

    public function validateStep(Request $request, $step) {

        if($step == 1) {
            
            $v = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6'
            ]);
            if ($v->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 422);
            }
           
        }else if ($step == 2) {
            
            $v = Validator::make($request->all(), [
                'organizationName' => 'required|string|max:255|unique:organizations,name',
            ]);
            if ($v->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 422);
            }
        }
    }
    /**
     * Login user and return a token
     */
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'  => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.errorLogin')
            ], 400);
        }

        $user = $this->guard()->user();
        
        
        $userData = new UserResource($user);

       
        return $this->respondWithToken($userData,$token);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\UserResource
     */

    public function update(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        if(!Auth::guard()->check()) {
            return response()->json([
                'Token Expired'
            ], 401);
        }


        $user_id = Auth::guard()->user()->id;
        $user = User::find($user_id);
       
        if (!$user)
        {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.user.notFound')
            ], 400);
        }
        $data = $request->toArray();

        if(strlen($data['password']) > 0) {
            //
            if(!Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('messages.password.wrong')
                ], 400);
            }
            $v = Validator::make($request->all(), [
                'password' => 'required|string|min:6',
                'new_password' => 'required|confirmed|string|min:6',
            ]);
            if ($v->fails())
            {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 422);
            }
            $data['password'] = Hash::make($data['new_password']);
        }else {
            $data['password'] = $user->password;
        }

        if(!$user->update($data)) {
            return response()->json([
                'status' => 'error',
                'msg' => __('messages.global.fail')
            ], 400);
        }
        return response()->json([
            'status' => 'success',
            'msg' => __('messages.global.success'),
            'user' => User::find($user_id)
        ], 200);
        
       
        // $data = $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email',
        // ]);

        // $user->update($data);

        // return new UserResource($user);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    public function authenticate(Request $request)
    {

        $user = $this->guard()->user();
        
        if(!$user) {
            return response()->json([
                'Token Expired'
            ], 401);
        }
        $userData = new UserResource($user);
        return response()->json([
            'status' => 'success',
            'user' => $userData
        ]);
    }
    /**
     * Logout User
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    /**
     * Send password reset link. 
     */
    public function sendPasswordResetLink(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        return $this->sendResetLinkEmail($request);
    }
    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Password reset email sent.',
            'data' => $response
        ]);
    }
    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'status' => 'error',
            'msg' => __('messages.email.fail')
        ], 400);

        //return response()->json(['message' => 'Email could not be sent to this email address.']);
    }
    /**
     * Handle reset password 
     */
    public function callResetPassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        return $this->reset($request);
    }
    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }
    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully.']);
    }
    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'status' => 'error',
            'msg' => __('messages.password.fail')
        ], 400);
        //return response()->json(['message' => 'Failed, Invalid Token.']);
    }
        
    /**
     * Refresh JWT token
     */
    public function refresh(Request $request)
    {
        // try {
        //     $token = $this->guard()->refresh();
        // } catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        //     return response()->json(['error' => $e->getMessage()], 401);
        // }
        
        // return response()->json([
        //     'token' => [
        //         'access' => $token,
        //         'token_type' => 'bearer',
        //         'expires_in' => auth()->factory()->getTTL() * 60,
        //     ]
        // ]);
        
    }
    
    /**
     * Return auth guard
     */
    private function guard()
    {
        return Auth::guard();
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($user, $token)
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => [
                'access' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ]
        ]);
    }
}
