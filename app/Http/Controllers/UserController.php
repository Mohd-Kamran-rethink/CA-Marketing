<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function listAgents(Request $req)
    {
        $searchTerm = $req->query('table_search');
        $stateFilter = $req->query('stateFilter');
        $languageFilter = $req->query('languageFilter');
        $agents = User::where('role', 'marketing_agent')
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                });
            })
            ->orderBy('users.id', 'desc')
            ->select('users.*') 
            ->paginate(10);


        return view('Admin.Agents.list', compact('agents', 'searchTerm'));
    }
    public function AgentView(Request $req)
    {
        $id = $req->query('id');
        if ($id) {
            $agent = User::where("role", '=', 'marketing_agent')->find($id);
            return view('Admin.Agents.add', compact('agent'));
        } else {
            return view('Admin.Agents.add');
        }
    }
    // common functions for manager and agents
    public function add(Request $req)
    {


        $rules = [
            'name' => 'required|unique:users,name',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:confirmPassword',
            'confirmPassword' => 'required|'
        ];

        $req->validate($rules);

        $user = new User();
        $user->name = $req->name;
        $user->phone = $req->phone;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->role = $req->role;

        $result = $user->save();
        if ($result) {
            return redirect('/agents')->with(['msg-success' => 'Agent has been added.']);
        } else {
            return redirect('/agents')->with(['msg-error' => 'something went wrong could not add agent']);
        }
    }
    public function edit(Request $req)
    {
        $agent = User::where("role", '=', $req->role)->find($req->userId);

        $rules = [
            'name' => 'required|unique:users,name,' . $agent->id,
            'phone' => 'required|unique:users,phone,' . $agent->id,
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'confirmPassword' => 'required_with:password',
        ];

        $conditionalRules = [
            'password' => 'nullable|min:8|same:confirmPassword',
        ];

        $req->validate(array_merge($rules, $conditionalRules));

        $agent->name = $req->name;
        $agent->phone = $req->phone;
        $agent->email = $req->email;

        if ($req->password) {
            $agent->password = Hash::make($req->password);
        }
        $result = $agent->save();
        if ($result) {
            return redirect('/agents')->with(['msg-success' => 'Agent has been updated']);
        } else {
            return redirect('/agents')->with(['msg-error' => 'Something went wrong could not update agent.']);
        }
    }
    public function delete(Request $req)
    {

        $User = User::where("role", '=', $req->role)->find($req->deleteId);
        $result = $User->delete();
        if ($result) {
            return redirect('/agents')->with(['msg-success' => 'Agent has been deleted']);
        } else {
            return redirect('/agents')->with(['msg-error' => 'Something went wrong could not delete agent.']);
        }
    }
}
