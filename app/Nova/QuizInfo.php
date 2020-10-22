<?php

namespace App\Nova;

use App\Nova\Actions\GenerateQuiz;
use App\Repositories\PushNotificationRepository;
use App\Repositories\QuizRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

use R64\NovaFields\Number;
use R64\NovaFields\Row;

use Laravel\Nova\Http\Requests\NovaRequest;

class QuizInfo extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\QuizInfo::class;

    public static $group = 'Quiz';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
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

            Boolean::make("Auto"),

            Text::make('Entry Fee (Coins)', 'entry_fee')->sortable(),
            Text::make('Total Participants', 'total_participants')->sortable(),
            Text::make('Total Winners', 'total_winners')->sortable(),
            Text::make('Required Participants', 'required_participants')->sortable(),
            Text::make('Total Questions', 'all_questions_count')->sortable(),
            Text::make('Answerable Questions', 'answerable_questions_count')->sortable(),

            DateTime::make('Expired At')
                ->exceptOnForms()
                ->sortable(),

            Text::make('Notify Before (Minutes)', 'notify')->sortable(),
            Text::make('Time Per Question (Seconds)', 'time')->sortable(),

            HasMany::make('Distribution', 'prize_distributions', PrizeDistribution::class),
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
        return [new GenerateQuiz(new QuizRepository(new PushNotificationRepository, new UserRepository))];
    }
}
