<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
   public function index()
   {
      $users = User::all();

      return view('admin.users', [
         'users' => $users,
      ]);
   }
   public function store(Request $request)
   {
      // 1. Validate the input
      $validated = $request->validate([
         'name'     => ['required', 'string', 'max:255'],
         'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
         'password' => ['required'],
      ]);

      // 2. Create the User
      User::create([
         'name'     => $validated['name'],
         'email'    => $validated['email'],
         'password' => Hash::make($validated['password']),
      ]);

      // 3. Redirect with the toast message we built earlier
      return redirect()->route('users.index')
         ->with('success', 'User created successfully!');
   }

   public function edit(User $user)
   {
      $users = User::all();
      return view('admin.users', compact('user', 'users'));
   }

   public function update(Request $request, User $user)
   {
      // 1. Validate
      $validated = $request->validate([
         'name'  => ['required', 'string', 'max:255'],
         'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
         'password' => ['nullable'],
      ]);

      // 2. Prepare data
      $user->name = $validated['name'];
      $user->email = $validated['email'];

      // 3. Only update password if a new one was provided
      if ($request->filled('password')) {
         $user->password = Hash::make($validated['password']);
      }

      $user->save();

      return redirect()->route('users.index')
         ->with('success', 'User updated successfully!');
   }

   public function destroy(User $user)
   {
      // Prevent a user from deleting themselves if they are logged in
      if (auth()->id() === $user->id) {
         return redirect()->back()->with('error', 'You cannot delete your own account!');
      }

      $user->delete();

      return redirect()->route('users.index')
         ->with('success', 'User deleted successfully.');
   }
}
