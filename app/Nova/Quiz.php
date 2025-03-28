<?php

namespace App\Nova;

use App\Nova\Actions\JoinQuiz;
use App\Nova\Actions\JoinBulkQuiz;
use App\Repositories\PushNotificationRepository;
use App\Repositories\QuizRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Quiz extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Quiz::class;

    public static $group = 'Quiz';

    public static $orderBy = ['expired_at' => 'desc'];

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

            Boolean::make('Private')->sortable(),

            Boolean::make('Pinned')->sortable(),

            Text::make('Title')->sortable(),

            Text::make("Status")->sortable(),

            DateTime::make('Expired At')
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make("Host", "host", User::class),

            BelongsTo::make("Quiz Info", "quiz_infos", QuizInfo::class),

            HasMany::make("Quiz Participants", "participants"),

            HasMany::make("Quiz Rankings", "rankings"),

            BelongsToMany::make("Questions", "questions", Question::class),

            BelongsToMany::make("Answerable Questions", "answerable_questions", Question::class),
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
        return [
            new JoinQuiz(new QuizRepository(new PushNotificationRepository, new UserRepository)),
            new JoinBulkQuiz(new QuizRepository(new PushNotificationRepository, new UserRepository)),
        ];
    }
}
