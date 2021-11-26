<?php

namespace Modules\AuthSaml2\Services;

use Modules\Users\Entities\User;
use Illuminate\Support\Facades\Hash;
use App\Models\District;
use Modules\LocaleModule\Entities\Locale;

class LoginUserService
{
    /**
     * @param $saml2UserParams
     * @return User
     */
    public function getUser($saml2UserParams)
    {
        $user = User::where('id', $saml2UserParams['uid'][0])->first();

        if (empty($user)) {
            $user = new User();
            //$user->id = $saml2UserParams['uid'][0] ?? null;
            $user->password = Hash::make($saml2UserParams['uid'][0]);
            $user->name = $saml2UserParams['displayName'][0] ?? null;
            //$user->displayName = $saml2UserParams['displayName'][0] ?? null;
            $user->email = $saml2UserParams['email'][0] ?? null;
            $user->isMemberOf = $saml2UserParams['isMemberOf'][0] ?? null;
            //$user->market = $saml2UserParams['market'][0] ?? null;
            $language = !is_null($saml2UserParams['language'][0]) ? $saml2UserParams['language'][0] : "english";
            $user->locale_id = $this->setLocale($language);
            //$user->surname = $saml2UserParams['surname'][0] ?? null;
            $user->attachRole($saml2UserParams['jobRole'][0]);
            $user->district_id = $this->setDistrictBySlug($saml2UserParams['district'][0]);
        }

        $user->save();

        return $user;
    }

    private function setDistrictBySlug($slug)
    {
        $district = District::where('slug',$slug)->first();
        if($district){
            return $district->id;
        }else{
            return 2;
        }
    }

    private function setLocale($locale)
    {
        $locale_id = Locale::where('name',$locale)->first();
        if($locale){
            return $locale_id;
        }else{
            return 2;
        }
    }
}
