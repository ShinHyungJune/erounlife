<?php

namespace App\Http\Middleware;

use App\Http\Resources\InformationResource;
use App\Http\Resources\UserResource;
use App\Models\Information;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $information = Information::first();

        return array_merge(parent::share($request), [
            "user" => auth()->user() ? UserResource::make(auth()->user()) : "",
            "information" => $information ? InformationResource::make(Information::first()) : "",
            "flash" => function() use ($request){
                return [
                    "success" => $request->session()->get("success"),
                    "error" => $request->session()->get("error"),
                ];
            }
        ]);
    }
}