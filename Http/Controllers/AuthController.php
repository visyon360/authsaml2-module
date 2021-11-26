<?php

namespace Modules\AuthSaml2\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Modules\AuthSaml2\Events\TrainingHubLoggined;
use Modules\AuthSaml2\Services\LoginUserService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Models\RegistrationToken;
use Aacotroneo\Saml2\Saml2Auth;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Http\Controllers\Saml2Controller;
use Illuminate\Support\Facades\Auth;


/**
 * Class AuthControllerTest
 *
 * @package Modules\PassportModule\Tests\Feature
 */
class AuthController extends Saml2Controller
{

    public function acs(Saml2Auth $saml2Auth, $idpName)
    {
        $errors = $saml2Auth->acs();

        if (!empty($errors)) {
            logger()->error('Saml2 error_detail', ['error' => $saml2Auth->getLastErrorReason()]);
            logger()->error('Saml2 error', $errors);

            return redirect(config('authsaml2.login-callback') . '?error=saml2error');
        }
        $saml2User = $saml2Auth->getSaml2User();
        $saml2UserParams = $saml2User->getAttributes();

        if (App::environment('production')) {
            $isMemberOf = $saml2UserParams['isMemberOf'];
            if (is_array($isMemberOf)) {
                $isMemberOf = collect($isMemberOf);
                $authorized = $isMemberOf->contains('CMC_2020');
            } else {
                $authorized = $isMemberOf === 'CMC_2020';
            }
            if (!$authorized) {
                return redirect(config('authsaml2.login-callback') . '?error=unauthorized');
            }
        }

        //DEBUG
        Log::debug($saml2UserParams);
        
        $user = (new LoginUserService)->getUser($saml2UserParams);

        event(new Saml2LoginEvent($idpName, $saml2User, $saml2Auth));
        Auth::login($user);

        TrainingHubLoggined::dispatch($user);

        $tokenData = $this->createRegistrationToken($user->id);

        // if (!config('auth.cupra_login_open')) {
        //     return redirect(config('saml2module.blocked-access'));
        // }
        return $this->successResponse([
            'token' => $tokenData,
        ], 'Authorized access', 200);
    }

    /**
     * Process an incoming saml2 logout request.
     * Fires 'Saml2LogoutEvent' event if its valid.
     * This means the user logged out of the SSO infrastructure, you 'should' log them out locally too.
     *
     * @param Saml2Auth $saml2Auth
     * @param $idpName
     */
    public function sls(Saml2Auth $saml2Auth, $idpName)
    {
        return redirect(config('authsaml2.auth'));
    }

    private function createRegistrationToken($user_id)
    {
        return RegistrationToken::create([
            'user_id' => $user_id,
            'token'   => md5(rand(1, 10) . microtime()),
        ]);
    }


}