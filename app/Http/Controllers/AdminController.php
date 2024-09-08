<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view("admin.index");
    }

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view("admin.profile", compact("profileData"));
    }

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file("photo")) {
            $file = $request->file("photo");
            $filename = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path("uploads/admin_images"), $filename);
            $data["photo"] = $filename;
        }

        $data->save();

        return redirect()->back();
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route("admin.login"));
    }

    public function AdminLogin()
    {
        return view("admin.login");
    }
}
