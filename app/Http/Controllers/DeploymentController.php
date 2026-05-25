<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class DeploymentController extends Controller
{
    public function setup(Request $request)
    {
        $configuredToken = (string) env('DEPLOY_SETUP_TOKEN', '');
        $providedToken = (string) $request->query('token', '');

        if ($configuredToken === '' || ! hash_equals($configuredToken, $providedToken)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $steps = [];

        Artisan::call('migrate', ['--force' => true]);
        $steps[] = [
            'step' => 'migrate',
            'output' => trim(Artisan::output()),
        ];

        if ($request->boolean('seed')) {
            Artisan::call('db:seed', [
                '--class' => 'UserSeeder',
                '--force' => true,
            ]);
            $steps[] = [
                'step' => 'seed',
                'output' => trim(Artisan::output()),
            ];
        }

        return response()->json([
            'ok' => true,
            'steps' => $steps,
        ]);
    }
}
