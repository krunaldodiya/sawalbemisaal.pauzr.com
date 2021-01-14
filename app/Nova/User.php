<?php

namespace App\Nova;

use App\Nova\Actions\WalletPoint;
use App\Nova\Filters\Version;
use App\Quiz;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\User::class;

    public static $group = 'User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email', 'mobile', 'username'
    ];

    public static $orderBy = ['created_at' => 'desc'];

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

            Image::make('Avatar'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make("Gender")->options([
                'Male' => 'Male',
                'Female' => 'Female',
                'None' => 'None',
            ])->sortable(),

            Text::make('Mobile')
                ->sortable()
                ->rules('required', 'max:254'),

            Text::make('Username')
                ->sortable()
                ->rules('required', 'max:254', 'regex:/^[\w-]*$/'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')

                ->updateRules('nullable', 'string', 'min:8'),

            Text::make('Version')->sortable(),

            Text::make('Referral Code', 'referral_code')
                ->sortable()
                ->rules('required', 'max:255')
                ->default(function () {
                    return  Quiz::generateTitle();
                }),

            HasOne::make('Wallet'),

            BelongsTo::make("Country"),

            BelongsToMany::make('Topics', 'topics', Topic::class)->searchable(),

            BelongsToMany::make('Followers', 'followers', User::class)->searchable(),

            BelongsToMany::make('Followings', 'followings', User::class)->searchable(),

            HasMany::make("Quizzes", "quizzes", QuizParticipant::class),

            HasMany::make("Invitations", "invitations", Invitation::class),

            Date::make('Joined On', "created_at")
                ->exceptOnForms()
                ->resolveUsing(function ($date) {
                    return $date->format('d/m/Y h:m A');
                })
                ->sortable(),

            Boolean::make('Admin'),

            Boolean::make('Demo'),

            Boolean::make('Status'),
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
        return [new Version()];
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
        return [new WalletPoint()];
    }
}
