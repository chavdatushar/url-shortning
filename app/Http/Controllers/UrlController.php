<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use App\Repositories\UrlRepository;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Str;

class UrlController extends Controller
{

    private $urlRepository;

    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->urlRepository->getDatatable();
        }

        return view('urls.index');
    }

    public function store(UrlRequest $request)
    {
        try {
            $user = Auth::user();
            $urlCount = Url::where('user_id', $user->id)->count();
            $planLimit = $user->plan == 'unlimited' ? PHP_INT_MAX : (int) $user->plan;
    
            if ($urlCount >= $planLimit) {
                return response()->json(['message' => 'You have reached your URL limit. Please upgrade your plan.','success' => false], 403);
            }
    
            $originalUrl = Crypt::encryptString($request->original_url);
            $shortUrl = Str::random(6);
    
            while (Url::where('short_url', $shortUrl)->exists()) {
                $shortUrl = Str::random(6);
            }
    
            $url = $this->urlRepository->create([
                'user_id' => Auth::id(),
                'original_url' => $originalUrl,
                'short_url' => $shortUrl
            ]);
            return response()->json([
                'data' => ['url' => $url],
                'success' => true,
                'message' => 'URL shortened successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(UrlRequest $request, Url $url)
    {
        try {
            $originalUrl = Crypt::encryptString($request->original_url);
            $url->update(['original_url' => $originalUrl]);
    
            return response()->json([
                'data' => ['url' => $url],
                'success' => true,
                'message' => 'URL updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
       
    }

    public function destroy($id)
    {
        try {
            $this->urlRepository->delete($id);
            return response()->json(['success' => true,'message' => 'URL deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
       
    }

    public function deactivate($id)
    {
        try {
            $url = $this->urlRepository->getById($id);
            
            $msg = $url->is_active?'URL deactivated successfully':'URL activated successfully';
            
            $url->is_active = $url->is_active?false:true;
            $url->save();
            return response()->json(['success' => true,'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        
    }

  


    public function redirect($shortUrl)
    {
        $url = Url::where('short_url', $shortUrl)->first();
        if ($url && $url->is_active) {
            return redirect()->to(Crypt::decryptString($url->original_url));
        } else {
            return abort(404);
        }
    }
}
