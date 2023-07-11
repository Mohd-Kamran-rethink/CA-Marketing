<?php

namespace App\Http\Controllers;

use App\BankDetail;
use App\Campaign;
use App\CampaignResult;
use App\CampaignTransaction;
use App\City;
use App\SocialAccount;
use App\State;
use App\TransactionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
  public function list(Request $req)
  {
    $startDate = $req->query('from_date') ?? null;
    $endDate = $req->query('to_date') ?? null;
    $type = $req->query('type') ?? 'null';
    $account_id = $req->query('account_id') ?? 'null';
    $agent_id = 'null';
    if(session('user')->role=='marketing_agent')
    {
      $agent_id=session('user')->id;
    }
    if (!$startDate) {
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
    } else {
        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
    }
    $accounts=SocialAccount::where('status','=','active')->get();
    $campaign = Campaign::leftJoin('states', 'campaigns.state_id', 'states.id')
      ->leftJoin('cities', 'campaigns.city_id', 'cities.id')
      ->leftJoin('social_accounts', 'campaigns.social_account_id', 'social_accounts.id')
      ->when($account_id !== 'null', function ($query) use ($account_id) {
        $query->where('campaigns.social_account_id', '=', intval($account_id));
      })
      ->when($agent_id !== 'null', function ($query) use ($agent_id) {
        $query->where('campaigns.agent_id', '=', intval($agent_id));  
      })
      ->select('campaigns.*', 'states.name as statename', 'cities.name as city','social_accounts.title as accountName')
      ->whereDate('campaigns.created_at', '>=', date('Y-m-d', strtotime($startDate)))
      ->whereDate('campaigns.created_at', '<=', date('Y-m-d', strtotime($endDate)))
      
      ->orderBy('campaigns.created_at', 'desc')
      ->paginate();
      $startDate = $startDate->toDateString();
      $endDate = $endDate->toDateString();
    return view('Admin.Campaign.list', compact('campaign','accounts','startDate','endDate'));
  }
  public function addView(Request $req)
  {
    $cities = [];
    $accounts=SocialAccount::where('status','=','active')->get();
    $states = State::where('country_id', '=', 101)->orderBy('name', 'asc')->get();
    if ($req->query('id')) {
      $campaign = Campaign::find($req->query('id'));
      if ($campaign->city_id && $campaign->state_id) {
        $cities = City::where('state_id', '=', $campaign->state_id)->get();
      }
      return view('Admin.Campaign.add', compact('states', 'accounts','campaign', 'cities'));
    }
    return view('Admin.Campaign.add', compact('states','accounts'));
  }
  public function renderCities(Request $req)
  {
    $cities = City::where('state_id', '=', $req->state_id)->get();
    $html = '<option value="0">--Choose--</option>';
    foreach ($cities as $key => $item) {

      $html .= '<option value="' . $item->id . '">' . $item->name . '</option>';
    }
    return $html;
  }
  public function add(Request $req)
  {
    $req->validate(['title' => 'required']);
    $campaign = new Campaign();
    $campaign->social_account_id = $req->account;
    $campaign->title = $req->title;
    $campaign->state_id = $req->state;
    $campaign->city_id = $req->city;
    $campaign->agent_id  = session('user')->id;
    $campaign->description = $req->description;
    $result = $campaign->save();
    if ($result) {
      return redirect('/campaigns')->with(['msg-success' => 'Campaign added successfully']);
    } else {
      return redirect('/campaigns')->with(['msg-error' => 'Somthing went wrong']);
    }
  }
  public function edit(Request $req)
  {
    $req->validate(['title' => 'required']);
    $campaign = Campaign::find($req->hiddenid);
    $campaign->title = $req->title;
    
    $campaign->social_account_id = $req->account;
    $campaign->state_id = $req->state;
    $campaign->city_id = $req->city;
    $campaign->description = $req->description;
    $result = $campaign->save();
    if ($result) {
      return redirect('/campaigns')->with(['msg-success' => 'Campaign added successfully']);
    } else {
      return redirect('/campaigns')->with(['msg-error' => 'Somthing went wrong']);
    }
  }
  public function changeStatus(Request $req)
  {
    $req->validate(['status' => 'required']);
    $campagin = Campaign::find($req->campagin);
    $campagin->status = $req->status;
    $result = $campagin->save();
    if ($result) {
      return redirect('campaigns')->with(['msg-success' => 'Campagin status has been changed']);
    }
  }
  public function addSpending(Request $req)
  {
    $req->validate(['amount' => 'required','bank'=>'required']);
    $campagin = Campaign::find($req->campaginID);
    if ($req->campaginID) {

      $campaginHistory = new CampaignTransaction();
      $campaginHistory->campaign_id = $req->campaginID;
      $campaginHistory->amount = $req->amount;
      $campaginHistory->banks_details_id  = $req->bank;
      $campaginHistory->save();
      $campagin->amount = $campagin->amount + $req->amount;
      $campagin->update();
      $bank=BankDetail::find($req->bank);
      if($bank)
      {
        $transacionHistory=new TransactionHistory();
        $transacionHistory->campaign_transactions_id=$campaginHistory->id;
        $transacionHistory->opening_balance=$bank->amount;
        $transacionHistory->agent_id=session('user')->id; 
        $transacionHistory->amount=$req->amount;
        $transacionHistory->bank_id=$bank->id;
        $transacionHistory->type='Withdraw';
        $transacionHistory->current_balance=$bank->amount-$req->amount;
        $transacionHistory->save();
        if($transacionHistory)
        {
          $bank->amount=$bank->amount-$req->amount;
          $result = $bank->update();
        }
      }
      if ($result) {
        return redirect('campaigns/view-details?id='.$campagin->id)->with(['msg-success' => 'Campagin spending added']);
      } else {
        return redirect('campaigns/view-details?id='.$campagin->id)->with(['msg-error' => 'Somthing went wrong']);
      }
    }
  }
  public function viewSpendings(Request $req)
  {
    $id = $req->query('id');
    $banks=BankDetail::where('type','=','marketing')->get();
    $startDate = $req->query('from_date') ?? null;
    $endDate = $req->query('to_date') ?? null;
    if (!$startDate) {
      $startDate = Carbon::now()->startOfDay();
      $endDate = Carbon::now()->endOfDay();
    } else {
      $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
      $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
    }
    $campaginHistory = CampaignTransaction::where('campaign_id', '=', $id)->whereDate('created_at', '>=', date('Y-m-d', strtotime($startDate)))
      ->whereDate('created_at', '<=', date('Y-m-d', strtotime($endDate)))
      ->orderBy('created_at','desc')
      ->get();
    $startDate = $startDate->toDateString();
    $endDate = $endDate->toDateString();
    return view('Admin.Campaign.details', compact('campaginHistory', 'id', 'startDate', 'endDate','banks'));
  }

  public function addResultForm(Request $req) {
      $result_id=$req->query('result_id');
      $id=$req->query('id');
      $campagin=Campaign::find($id);
      if($result_id)
      {
        $result=CampaignResult::find($result_id);
        return view('Admin.Campaign.addResult',compact('campagin','result'));
      }
      else
      {
        return view('Admin.Campaign.addResult',compact('campagin'));
      }

  }
  function addResult(Request $req) 
  {
    $req->validate(
      [
        'date'=>'required',
      ]);
      $campaignResult=new CampaignResult();
      $campaignResult->campaign_id=$req->campaign;
      $campaignResult->whatsapp_messages=$req->whatsappMessage;
      $campaignResult->messanger=$req->messanger;
      $campaignResult->leads=$req->leads;
      $campaignResult->impresssions=$req->impression;
      $campaignResult->lead_reach=$req->lead_reach;
      $campaignResult->link_clicks=$req->link_clicks;
      $campaignResult->landing_page_views=$req->landing_page_views;
      $campaignResult->date=$req->date;
      $result=$campaignResult->save();
      if ($result) {
        return redirect('/campaigns')->with(['msg-success' => 'Result added successfully']);
      } else {
        return redirect('/campaigns')->with(['msg-error' => 'Somthing went wrong']);
      }
      
  }
  public function viewResults(Request $req)
  {
    $id=$req->query('id');
    $startDate = $req->query('from_date') ?? null;
    $endDate = $req->query('to_date') ?? null;
    if (!$startDate) {
      $startDate = Carbon::now()->startOfDay();
      $endDate = Carbon::now()->endOfDay();
    } else {
      $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
      $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
    }
    $campaignResult=CampaignResult::where('campaign_id','=',$id)
                                  ->whereDate('date', '>=', date('Y-m-d', strtotime($startDate)))
                                  ->whereDate('date', '<=', date('Y-m-d', strtotime($endDate)))
                                  ->orderBy('date','desc')
                                  ->get();
    $startDate = $startDate->toDateString();
    $endDate = $endDate->toDateString();
    return view('Admin.Campaign.viewResults',compact('campaignResult','id','startDate','endDate'));
  }
  function editResult(Request $req) 
  {
    $req->validate(
      [
        'date'=>'required',
      ]);
      $campaignResult=CampaignResult::find($req->resultID);
      $campaignResult->campaign_id=$req->campaign;
      $campaignResult->whatsapp_messages=$req->whatsappMessage;
      $campaignResult->messanger=$req->messanger;
      $campaignResult->leads=$req->leads;
      $campaignResult->impresssions=$req->impression;
      $campaignResult->lead_reach=$req->lead_reach;
      $campaignResult->link_clicks=$req->link_clicks;
      $campaignResult->landing_page_views=$req->landing_page_views;
      $campaignResult->date=$req->date;
      $result=$campaignResult->save();
      if ($result) {
        return redirect('/campaigns/view-results?id='.$req->campaign)->with(['msg-success' => 'Result added successfully']);
      } else {
        return redirect('/campaigns/view-results?id='.$req->campaign)->with(['msg-error' => 'Somthing went wrong']);
      }
      
  }
}
