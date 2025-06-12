<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Mail\SendUserLoginDetailsMail;
use App\Models\User;
use App\Services\MediaUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $userAccount = auth()->user()->userAccount;
        $restaurants = $userAccount->restaurants;
        $users = collect();
        foreach ($restaurants as $restaurant) {
            foreach ($restaurant->users as $user) {
                $users->add($user);
            }
        }

        return theme_view('users.index', [
            'users' => $users,
            'restaurants' => $restaurants,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return theme_view('users.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, MediaUploadService $mediaUploadService)
    {
        try {
            DB::beginTransaction();

            // Initialize $requestData
            $requestData = [];
            // Check if a photo was uploaded
            if ($request->hasFile('photo')) {

                $file = $request->file('photo');

                $path = $mediaUploadService->upload($file, 'uploads/users/images', 'image');

                $requestData['photo'] = $path;
                if (!$path) {
                    return back()->with('error', 'Invalid file type.');
                }
            }
            // Merge additional data into the request
            $request->merge([
                'restaurant_id' => auth()->user()->restaurant_id,
                'user_id' => auth()->user()->userAccount->id,
            ]);

            $user = User::create(array_merge($request->all(), $requestData));

            // Assign roles if any
            if (method_exists($user, 'getRoleIds') && $user->getRoleIds()) {
                $user->roles()->sync($user->getRoleIds());
            }

            $password = $request->input('password');
            Mail::to($user->email)->send(new SendUserLoginDetailsMail($user, $password));

            DB::commit();
            return redirect()->route('users.index')->with('success_message', 'User Created Successfully');
        } catch (\Exception $e) {
            logger()->error('Error creating user: ' . $e->getMessage());
            DB::rollBack();
            return back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return theme_view('users.form', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Initialize $requestData
        $requestData = [];
        $user = User::findOrFail($id);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Check if a new photo was uploaded
        if ($request->hasFile('profile_image')) {
            // Get the original name of the uploaded file
            $newPhotoName = $request->file('profile_image')->getClientOriginalName();

            // Check if any user already has a photo with the same filename
            $existingPhoto = User::where('profile_image', $newPhotoName)->exists();


            if (!$existingPhoto) {
                // Store the new photo and update the photo path in the request data
                $photoPath = $request->file('profile_image')->store('restaurant/users/photos', 'public');
                $requestData['profile_image'] = $photoPath;
            } else {
                // Use the existing photo path
                $requestData['profile_image'] = $user->photo;
            }
        }

        $user->update(array_merge($request->except(['password']), $requestData));
        // Assign roles if any
        if (method_exists($user, 'getRoleIds') && $user->getRoleIds()) {
            $user->roles()->sync($user->getRoleIds());
        }

        return redirect()->route('users.index')->with('success_message', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        if ($user->barOrders()->exists() || $user->restaurantOrders()->exists() || $user->roomReservations()->exists()) {
            return back()->with('error_message', 'User cannot be deleted because they have related transactions.');
        }
        $user->delete();
        return back()->with('success_message', 'User deleted successfully');
    }

    public function loginDetails($user)
    {
        (new RegistrationService)->sendLoginDetails($user);
        return redirect()->route('dashboard.users.index')->with('success_message', 'Login details sent successfully.');
    }

    public function setUserOutlet(Request $request)
    {
        $outletId = $request->input('outlet_id');
        session()->put('outlet_id', $outletId);
        $request->user()->update(['outlet_id' => $outletId]);
        return response()->json(['message' => 'Session updated successfully']);
    }

    public function setUserHotel(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $restaurantId = $request->input('restaurant_id');
        $request->user()->update(['restaurant_id' => $restaurantId]);

        return redirect()->back()->with('success', 'Hotel changed successfully!');
    }

    public function restore(User $user, $id) {}

    public function search(Request $request)
    {
        $searchValue = $request->input('search');

        // Perform the search based on partial string match
        $users = User::where('restaurant_id', auth()->user()->restaurant_id)->where(function ($query) use ($searchValue) {
            $query->where('name', 'LIKE', "%$searchValue%")
                ->orwhere('email', 'LIKE', "%$searchValue%")
                ->orwhere('phone', 'LIKE', "%$searchValue%");
        })
            ->paginate(50);

        // Return only the HTML of the updated table rows
        return view('dashboard.user-management.search-results', [
            'users' => $users,
        ]);
    }

    public function assignExistingRolesToUsers()
    {
        // Assuming roles are stored in a 'roles' table and 'role_user' is the pivot table
        foreach (User::all() as $user) {
            switch ($user->role) {
                case 'Receptionist':
                    // Find the role by name
                    $role = Role::where('name', 'Receptionist')->first();

                    if ($role) {
                        // Check if the user already has this role
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();

                        // If the role is not assigned, assign it
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                // Add other cases here for different roles
                case 'Manager':
                    $role = Role::where('name', 'Manager')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                case 'Sales':
                    $role = Role::where('name', 'Sales')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                case 'Account':
                    $role = Role::where('name', 'Accounts')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                case 'Cashier':
                    $role = Role::where('name', 'Cashier')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                case 'Maintenance Officer':
                    $role = Role::where('name', 'Maintenance')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                case 'Store':
                    $role = Role::where('name', 'Store')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                case 'Housekeeper':
                    $role = Role::where('name', 'Housekeeping')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                case 'Hotel_Owner':
                    $role = Role::where('name', 'Hotel Owner')->first();
                    if ($role) {
                        $hasRole = DB::table('role_user')
                            ->where('user_id', $user->id)
                            ->where('role_id', $role->id)
                            ->exists();
                        if (!$hasRole) {
                            $user->roles()->attach($role->id);
                        }
                    }
                    break;

                // Add additional roles as needed...

                default:
                    // No action if the role doesn't match any cases
                    break;
            }
        }
        return back()->with('success', 'Done');
    }

    public function setShift(Request $request)
    {
        // Validate the shift date
        $request->validate([
            'shift_date' => 'required|date',
        ]);
        // Update the user's current shift date
        $shift_date = $request->input('shift_date');
        $request->user()->update(['current_shift' => $shift_date]);
    }
}
