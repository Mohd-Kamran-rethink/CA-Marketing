<?php

namespace App\Http\Controllers;

use App\MarketingSource;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function list(Request $req)
    {
        $searchTerm = $req->query('table_search');
        $sources = MarketingSource::when($searchTerm, function ($query, $searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('Admin.Sources.list',compact('sources','searchTerm'));
    }
    public function addView(Request $req)
    {
        $id = $req->query('id');
        if ($id) {
            $source = MarketingSource::find($id);
            return view('Admin.Sources.add', compact('source'));
        } 
        return view('Admin.Sources.add');
    }
    public function add(Request $req)
    {
        $req->validate([ 'name' => 'required|unique:sources,name',]);
        $source=new MarketingSource();
        $source->name=$req->name;
        $result=$source->save();
        if ($result) {
                return redirect('/sources')->with(['msg-success' => 'Source has been added.']);
        } else {
            return redirect('/sources')->with(['msg-error' => 'Something went wrong could not add source.']);
        }
    }
    public function delete(Request $req)
    {
        $source=MarketingSource::find($req->deleteId);
        $result= $source->delete();
        if ($result) {
            return redirect('/sources')->with(['msg-success' => 'Source has been deleted.']);
        } else {
            return redirect('/sources')->with(['msg-error' => 'Something went wrong could not delete source.']);
            }
    }
    public function edit(Request $req)
    {
        $req->validate([ 'name' => 'required|unique:sources,name,'. $req->sourceId,]);
        $source=MarketingSource::find($req->sourceId);
        $source->name=$req->name;
        $result=$source->update();
        if ($result) {
            return redirect('/sources')->with(['msg-success' => 'Source has been updated.']);
        } else {
            return redirect('/sources')->with(['msg-error' => 'Something went wrong could not update source.']);
        }
    }
}
