<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\AdminController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SellerController extends AdminController
{
    public function index()
    {
        $sellers = User::where('role', 'seller')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.sellers', compact('sellers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'seller',
        ]);

        return redirect()->route('admin.sellers')->with('success', 'Seller account created successfully.');
    }

    public function update(Request $request, $id)
    {
        $seller = User::where('role', 'seller')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $seller->id,
            'password' => 'nullable|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $seller->name = $request->name;
        $seller->email = $request->email;

        if ($request->filled('password')) {
            $seller->password = Hash::make($request->password);
        }

        $seller->save();

        return redirect()->route('admin.sellers')->with('success', 'Seller updated successfully.');
    }

    public function destroy($id)
    {
        $seller = User::where('role', 'seller')->findOrFail($id);
        $seller->delete();

        return redirect()->route('admin.sellers')->with('success', 'Seller removed from the system.');
    }
}
