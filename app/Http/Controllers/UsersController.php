<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index() {
        $users = User::all();
        $user = Auth::user();
        $data_user = User::orderByRaw("role = 'admin' DESC")->orderBy('created_at', 'desc')->get();
        return view('admin.users', ['title' => 'User', 'data' => $data_user, 'users' => $users, 'user' => $user]);
    }

    public function store(Request $request) {
        $request -> validate( [
            'badge' => 'required|string|min:8|max:8',
            'name' => 'required|min:4',
            'email' => 'required',
            'contact' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]); 

        $data = [
            'badge' => $request->badge,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('Password123'),
            'contact' => $request->contact,
            'role' => $request->role,
            'status' => $request->status,
        ];

        User::create($data);

        return redirect('/users')->with('success', 'Data has been successfully added.');
    }

    public function edit($id) {
        $data_user = User::findOrFail($id);

        return response()->json($data_user);
    }

    public function update($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'badge' => 'required|string|size:8',
            'name' => 'required|min:4',
            'email' => 'required',
            'password' => 'nullable|min:8',
            'contact' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('modal', [
                    'type' => 'user',
                    'action' => 'edit',
                    'id' => $id
                ]);
        }

        $data = [
            'badge' => $request->badge,
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        User::where('id', $id)->update($data);

        return redirect('users')->with('success', 'Data has been success updated');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/users')->with('success', 'Data has been successfully deleted');

    }

    public function indexUser() {
        $data_user = User::get();
        $user = Auth::user();
        return view('user.profile',['title' => 'Profile', 'data' => $data_user , 'user' => $user]);
    }
    public function updateUser($id,Request $request) {
        $request->validate([
            'badge' => 'required|string|max:8|min:8',
            'name' => 'required|min:4',
            'email' => 'required',
            'contact' => 'required',
            'password' => 'nullable|min:8'
        ]);
        $data = [
            'badge' => $request->badge,
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        User::where('id', $id)->update($data);

    return back()->with('success', 'Profile berhasil diupdate');
    }
}
