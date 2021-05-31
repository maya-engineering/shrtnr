<?php
namespace  App\Repositories;

use http\Url;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLink;
use App\Models\Click;
use Carbon\Carbon;

class UrlShortenerRepository
{

    public function createUrlGuest(array $data)
    {
        $checkLink = UserLink::where([
            ['user_id', NULL],
            ['original_link', $data['original_link']] 
        ])->get();

        $userLink = new UserLink;
        $userLink->original_link = $data['original_link'];
        $userLink->short_link = $checkLink->count() > 0 ? $checkLink[0]->short_link : $this->getShortUrl(7);
        $userLink->expire_date = $checkLink->count() > 0 ? $checkLink[0]->expire_date : Carbon::now()->addDays(3);
        $checkLink->count() == 0 ? $userLink->save() : '';

        return $userLink;
    }

    public function createUrlUser(array $data)
    {

        $checkLink = UserLink::where([
            ['user_id', $data['user_id']],
            ['original_link', $data['original_link']] 
        ])->get();

        $userLink = new UserLink;
        $userLink->user_id = $data['user_id'];
        $userLink->original_link = $data['original_link'];
        $userLink->short_link = $checkLink->count() > 0 ? $checkLink[0]->short_link : $this->getShortUrl(7);
        $userLink->expire_date = $checkLink->count() > 0 ? $checkLink[0]->expire_date : Carbon::now()->addDays(3);
        $checkLink->count() == 0 ? $userLink->save() : '';
        $userLink->id = $checkLink->count() == 0 ? $userLink->id : $checkLink[0]->id;

        $this->createClick($userLink->user_id ,$userLink->id);

        return $userLink;
    }  

    public function updateUrl(array $data)
    {

        $userlink = UserLink::where([
            ['user_id', $data['user_id']],
            ['original_link', $data['original_link']] 
        ])->firstOrFail();

        $userlink->short_link = $this->getShortUrl(7);
        $userlink->save();

        return $userlink;
    }

    public function getAllUserLinks()
    {
        return UserLink::where('user_id',Auth::user()->id)
            ->with('click')
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getShortUrl($len){

        $permitted_chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $input_len = strlen($permitted_chars);
        $random_string = '';

        for($i = 0; $i < $len; $i++) {

            $random_character = $permitted_chars[mt_rand(0, $input_len - 1)];
            $random_string .= $random_character;

        }

        return $random_string;
    }

    public function createClick($user_id, $user_link_id){
        $linkClick = new Click;
        $linkClick->user_id = $user_id;
        $linkClick->user_link_id = $user_link_id;
        $linkClick->click_count = 0;
        $linkClick->save();
    }

    public function updateClick(array $data)
    {
        $click = Click::where([
            ['user_id', $data['user_id']],
            ['user_link_id', $data['user_link_id']] 
        ])->firstOrFail();

        $click->click_count = $click->click_count + 1;
        $click->save();

        return $click;
    }

}