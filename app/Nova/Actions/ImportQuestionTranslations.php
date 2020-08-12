<?php

namespace App\Nova\Actions;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Collection;

use Anaseqal\NovaImport\Actions\Action;

use Laravel\Nova\Fields\ActionFields;

use Laravel\Nova\Fields\File;

use App\QuestionTranslation;

use Maatwebsite\Excel\Facades\Excel;

class ImportQuestionTranslations extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return __('Import Question Translations');
    }

    /**
     * @return string
     */
    public function uriKey() :string
    {
        return 'import-question-translations';
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        $data = Excel::import(new QuestionTranslation, $fields->file);

        $records = $data[0];

        unset($records[0]);

        QuestionTranslation::insert($records);

        return Action::message('Success!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make('File')->rules('required'),
        ];
    }
}
