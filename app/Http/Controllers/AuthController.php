<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index(){
        return view("auth.login");
    }
    
    public function checkLogin(LoginRequest $request){
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            return response()->json(["message"=>"Login Successful",'success' => true],200);
        }
        return response()->json(["message"=>"Invalid Credentials",'success' => false],401);
    }

    public function register(){
        return view("auth.register");
    }

    public function doRegister(RegisterRequest $request)
    {
        $user =$this->userRepository->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'data' => ['user' => $user],
            'success' => true,
            'message' => 'Registration successful.'
        ]);
        
    }

    public function logout(Request $request)
    {
        
        if ($request->ajax()) {
            Auth::logout();
            return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Something went wrong.'], 401);
    }

    public function showPlanUpgradeForm()
    {
        return view('urls.plan-upgrade');
    }
    public function upgradePlan(Request $request)
    {
        try {
            $request->validate([
                'plan' => 'required|in:10,1000,unlimited',
            ]);
            $this->userRepository->update(Auth::id(),['plan'=>$request->plan]);
            return response()->json(['message' => 'Plan upgraded to ' . $request->plan . ' URLs','success' => true], 200);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
       
    }
}
