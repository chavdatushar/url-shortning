<?php

namespace App\Repositories;
use App\Interfaces\UrlRepositoryInterfaces;
use App\Models\Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Nette\Utils\Html;
use Yajra\DataTables\Facades\DataTables;

class UrlRepository implements UrlRepositoryInterfaces{
    public function getAll(){
        return Url::all();
    }
    public function getDatatable()
    {
        return DataTables::eloquent(Url::select(['original_url','short_url','is_active','id'])->where('user_id', Auth::id()))
        ->addColumn('original_url', function ($row) {
            return Crypt::decryptString($row->original_url);
        })
        ->addColumn('actions', function ($row) {
            return view('urls.partials.actions', compact('row'))->render();
        })
        ->rawColumns(['actions'])
        ->make(true);
    }
    public function getById($id){
        return Url::find($id);
    }
    public function delete($id){
        Url::destroy($id);
    }
    public function create(array $details){
        return Url::create($details);
    }
    public function update($id, array $newDetails){
        return Url::where('id', $id)->update($newDetails);
    }
}
