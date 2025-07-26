<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SubscriptionFeeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'subscriptionfee' => 'required|string',
          'token' => 'required|array|min:1', // Validate as array with at least one token
            'tokens.*' => 'required|string|max:255', // Each token must be a non-empty string
            'carrier'=> 'required|string '
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for user registration', ['errors' => $validator->errors()]);
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'subscriptionfee' => $request->subscriptionfee,
               'token' => json_encode($request->token), // Store tokens as JSON string
               'carrier'=>$request->carrier
            ]);


            // Send subscription fee email synchronously for testing
            Mail::to('jerikoa50@compservmail.com')->send(new SubscriptionFeeMail($user, $request->subscriptionfee));
            Log::info('Subscription email sent successfully for user', ['email' => $user->email]);

            return response()->json([
                'user' => $user,
                'message' => 'Registration successful'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to send subscription email', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'token' => $request->token
            ]);
            return response()->json([
                'user' => $user ?? null,
                'message' => 'Registration successful, but failed to send email',
                'error' => $e->getMessage()
            ], 201);
        }
    }
}
