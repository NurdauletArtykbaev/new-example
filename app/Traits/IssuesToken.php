<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait IssuesToken
{
    public function issueToken(Request $request, $grant = 'password', $scope = '') {
        $params = [
            'grant_type'    => $grant,
            'client_id'     => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'scope'         => $scope
        ];

        if ($grant === 'password') {
            $request->request->add(['username' => (string) $request->get('username')]);
        }

        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST', $request->request->all());
        $pipeline = app()->handle($proxy);

        if (! $pipeline->isSuccessful()) {
            $pipeline->throwResponse();
        }

        return $pipeline;
    }
}
