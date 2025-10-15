<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Check if a usertype filter is selected
        $usertypeFilter = $request->input('usertype', null);

        // If a filter is selected, filter users based on the selected usertype
        if ($usertypeFilter) {
            $users = User::where('usertype', $usertypeFilter)->orderBy('id', 'desc')->paginate(2);
            $totalFiltered = User::where('usertype', $usertypeFilter)->count();
        } else {
            $users = User::orderBy('id', 'desc')->paginate(2);
            $totalFiltered = User::count(); // Total users count when no filter is applied
        }

        // Count the total number of users and the count of admins and users
        $totalAdmins = User::where('usertype', 'admin')->count();
        $totalUsers = User::where('usertype', 'user')->count();
        $total = User::count();

        return view('admin.user.index', compact('users', 'total', 'totalAdmins', 'totalUsers', 'usertypeFilter', 'totalFiltered'));
    }



    public function create()
    {

        return view('admin.user.create');
    }
    public function save(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => ['nullable', 'string', 'max:9'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $imageName = 'default.jpg';

        // Check if image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/users/'), $imageName);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $imageName,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        event(new Registered($user));

        if ($user) {
            event(new Registered($user));

            return redirect()
                ->route('admin.users')
                ->with('success', 'User "' . $user->firstname . ' ' . $user->lastname . '" Added Successfully!');
        }

        return back()
            ->withInput() // This will retain input values in case of validation error
            ->withErrors(['error' => 'Failed to add user "' . $user->firstname . ' ' . $user->lastname . '". Please try again.']);
    }
    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('admin.user.update', compact('users'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $id],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validate image
            'phone' => ['nullable', 'string', 'max:9'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/users/'), $imageName);

            // Delete old image if it exists
            if ($user->image && $user->image !== 'default.jpg') {
                $oldImagePath = public_path('images/users/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $user->image = $imageName;
        }

        // Update user data
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($user->save()) {
            session()->flash('success', 'User "' . $user->firstname . ' ' . $user->lastname . '" Updated Successfully!');
            return redirect(route('admin.users'));
        } else {
            session()->flash('error', 'Some Problem Occurred!');
            return redirect(route('admin.users'));
        }
    }
    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'usertype' => 'required|in:user,admin',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->usertype = $request->usertype;
        if ($user->save()) {
            session()->flash('success', 'User Type "' . $user->firstname . ' ' . $user->lastname . '" Updated Successfully!');
        } else {
            session()->flash('error', 'User Type "' . $user->firstname . ' ' . $user->lastname . '" Updated Failed!');
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            session()->flash('error', 'User "' . $user->firstname . ' ' . $user->lastname . '" Not Found!');
            return redirect()->route('admin.users');
        }

        if ($user->image && $user->image !== 'default.jpg') {
            $imagePath = public_path('images/users/' . $user->image);

            // Delete image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($user->delete()) {
            session()->flash('success', 'User "' . $user->firstname . ' ' . $user->lastname . '" Deleted Successfully!');
        } else {
            session()->flash('error', 'User Not Deleted!');
        }

        return redirect()->route('admin.users');
    }
}
