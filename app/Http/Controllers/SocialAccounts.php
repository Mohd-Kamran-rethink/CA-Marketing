<?php

namespace App\Http\Controllers;

use App\MarketingSource;
use App\SocialAccount;
use App\User;
use Illuminate\Http\Request;

class SocialAccounts extends Controller
{
    public function list(Request $req) 
    {
        $agent_id=null;
        if(session('user')->role=='marketing_agent')
        {
            $agent_id=session('user')->id;
        }
        $searchTerm = $req->query('table_search');
        $accounts=SocialAccount::leftJoin('users','social_accounts.agent_id','users.id')
                                ->leftJoin('marketing_sources','social_accounts.marketing_source_id','marketing_sources.id')
                                ->when($searchTerm, function ($query, $searchTerm) {
                                    $query->where(function ($query) use ($searchTerm) {
                                        $query->where('title', 'like', '%' . $searchTerm . '%');
                                        $query->orwhere('users.name', 'like', '%' . $searchTerm . '%');
                                    });
                                })
                                ->when($agent_id, function ($query, $agent_id) {
                                    $query->where(function ($query) use ($agent_id) {
                                        $query->where('social_accounts.agent_id', '=',$agent_id);
                                    });
                                })
                                ->select('social_accounts.*','users.name as agentName','marketing_sources.name as sourcename')
                                ->orderBy('id','desc')
                                ->paginate();
        return view('Admin.SocialAccounts.list',compact('accounts','searchTerm'));
    }
    public function addView(Request $req) {
        $agents=User::where('role','=','marketing_agent')->get();
        $sources=MarketingSource::where('status','=','active')->get();
        if($req->query('id'))
        {
            $account=SocialAccount::find($req->query('id'));
            return view('Admin.SocialAccounts.add',compact('sources','agents','account'));
        }
        return view('Admin.SocialAccounts.add',compact('sources','agents'));
    }
    public function add(Request $req) {
        $req->validate([
                        'title'=>'required',
                        'agent'=>'required|not_in:0',
                        'source'=>'required|not_in:0',
                        'currency'=>'required|not_in:0',
        ]);
        $account=new SocialAccount();
        $account->title=$req->title;
        $account->agent_id=$req->agent;
        $account->marketing_source_id=$req->source;
        $account->currency=$req->currency;
        $account->total_value=$req->total_value;
        $account->status='active';
        
        $account->provider=$req->provider;
        $result=$account->save();
        if($result)
        {
            return redirect('/accounts')->with(['msg-success'=>'Account added successfully']);
        }
        else
        {
                return redirect('/accounts')->with(['msg-error'=>'Somthing went wrong']);
        }
                
    }
    public function edit(Request $req)
    {
        $id=$req->accountID;
        $account=SocialAccount::find($id);
        $account->title=$req->title;
        $account->agent_id=$req->agent;
        $account->marketing_source_id=$req->source;
        $account->currency=$req->currency;
        $account->total_value=$req->total_value;
        $account->provider=$req->provider;
        $result=$account->save();
        if($result)
        {
            return redirect('/accounts')->with(['msg-success'=>'Account updated successfully']);
        }
        else
        {
                return redirect('/accounts')->with(['msg-error'=>'Somthing went wrong']);
        }
    }
    public function changeStatus(Request $req) 
    {
       $rules=['status'=>'required|not_in:0'];
       if($req->status=='banned')
       {
           $rules['holding_funds']='required';
       }
       $req->validate($rules);
       $account=SocialAccount::find($req->account);
       $account->status=$req->status;
       $account->holding_funds=$req->holding_funds;
       $result=$account->update();
       if($result)
        {
            return redirect('/accounts')->with(['msg-success'=>'Status has been changed successfully']);
        }
           
    }
}
