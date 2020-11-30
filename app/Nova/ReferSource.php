<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;

use Illuminate\Http\Request;

use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ReferSource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\ReferSource::class;

    public static $group = 'Referrals';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Refer Source', 'refer_source'),

            // Text::make('Languages', 'languages')->sortable(),

            Text::make('Ip Address', 'ip_address')->sortable(),
            Text::make('Device', 'device')->sortable(),
            Text::make('Platform', 'platform')->sortable(),
            Text::make('Platform Version', 'platform_version')->sortable(),
            Text::make('Browser', 'browser')->sortable(),
            Text::make('Browser Version', 'browser_version')->sortable(),
            Text::make('Robot', 'robot')->sortable(),
            DateTime::make('created_at')->sortable(),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
