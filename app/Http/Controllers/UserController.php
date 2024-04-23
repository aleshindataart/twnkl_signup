<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use SebastianBergmann\Type\FalseType;

class UserController extends Controller {
    public function signup(Request $request) {
        try {

            // Validate IP.
            if (!$this->isValidIP($request)) {
                return response()->json(['error' => 'Your IP address is blacklisted.'], 403);
            }

            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'user_type' => 'required|in:student,teacher,parent,private_tutor',
                'password' => 'required|min:4',
            ]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Create new user.
        $user = new User();
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->user_type = $validatedData['user_type'];
        $user->password = $validatedData['password'];
        $user->save();

        // Send welcome email.
        $this->sendWelcomeEmail($user);

        // return JSON response.
        return response()->json(['user' => $user], 200);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isValidIP(Request $request): bool {
        $blacklistedIPs = ['192.0.2.0', '203.0.113.0', '198.51.100.0'];
        $userIP = $request->ip();

        if (in_array($userIP, $blacklistedIPs)) {
            return FALSE;
        }
        return TRUE;
    }

    private function sendWelcomeEmail(User $user) {
        // Send welcome email to user
        switch ($user->user_type) {
            case 'student':
                $message = 'Welcome, dear student!';
                break;
            case 'teacher':
                $message = 'Welcome, esteemed teacher!';
                break;
            case 'parent':
                $message = 'Welcome, caring parent!';
                break;
            case 'private_tutor':
                $message = 'Welcome, dedicated private tutor!';
                break;
            default:
                $message = 'Welcome!';
                break;
        }

        // mail($user->email, 'Welcome to our platform', $message);
    }

    public function getUsers(Request $request) {
        try {
            $users = User::all();
            return response()->json(['users' => $users], 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
