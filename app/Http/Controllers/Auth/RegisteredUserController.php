<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\RestaurantSetupService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return theme_view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'numeric', 'digits:11', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //to remove bot registrations
        if (isset($request->myfield)) {
            abort(404);
        }

        DB::transaction(function () use ($request) {
            $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->firstOrFail();

            $user = User::create([
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'email' => $request->input('email'),
                'role_id' => $superAdminRole->id,
                'password' => Hash::make($request->input('password')),
                'phone' => $request->input('phone'),
            ]);

            if ($user->is_super_admin) {
                // No need for another transaction here
                $restaurant = (new RestaurantSetupService)->createWithDefaults([
                    'user_id' => $user->id,
                    'name' => 'Main Restaurant',
                ]);

                $user->update([
                    'restaurant_id' => $restaurant->id,
                ]);
            }

            // Assign roles if any
            if (method_exists($user, 'getRoleIds') && $user->getRoleIds()) {
                $user->roles()->sync($user->getRoleIds());
            }

            event(new Registered($user));

            Auth::login($user);
        });

        return redirect(route('dashboard', absolute: false));
    }
}
