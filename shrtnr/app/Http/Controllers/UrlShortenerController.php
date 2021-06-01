<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UrlShortenerRepository;
use App\Http\Requests\UrlGuestRequest;
use App\Http\Requests\UrlUserRequest;
use App\Http\Requests\UrlUpdateRequest;
use App\Models\UserLink;
use Illuminate\Support\Facades\Auth;

class UrlShortenerController extends Controller
{
    private $repository;

    public function __construct(UrlShortenerRepository $repository){

        $this->repository = $repository;

    }

    public function getLocalUrl($shortLink) :string 
    {
        $local =env('APP_URL');

        if(strpos($local,'localhost')){

            $local = $local.':8000';

        }

        return $local.'/'.$shortLink;
    }

    public function urlShortenerGuest(UrlGuestRequest $request) {

        $url = $this->repository->createUrlGuest($request->all());

        return response()->json(['status' => 200, 'message' => 'Url Shortened Successfully', 'originalURL' => $url->original_link, 'shortUrl' => $this->getLocalUrl($url->short_link)],200);

    }

    public function urlShortenerUser(UrlUserRequest $request){

        $url = $this->repository->createUrlUser($request->all());

        return response()->json(['status' => 200, 'message' => 'Your Url Shortened Successfully', 'link_id' => $url->id, 'originalURL' => $url->original_link, 'shortUrl' => $this->getLocalUrl($url->short_link)],200);
    }

    public function getAllLinks()
    {
        return $this->repository->getAllUserLinks();
    }

    public function updateLink(UrlUpdateRequest $request){

        $url = $this->repository->updateUrl($request->all());

        return response()->json(['status' => 200, 'message' => 'Url Updated Successfully', 'originalURL' => $url->original_link, 'shortUrl' => $this->getLocalUrl($url->short_link)],200);

    }

    public function updateClick(Request $request){

        $clickUpdate = $this->repository->updateClick($request->all());

        return response()->json(['status' => 200, 'message' => 'Click Count Updated!'],200);

    }


}
