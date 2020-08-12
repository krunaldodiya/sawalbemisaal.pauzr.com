<?php

namespace App\Imports;

use App\QuestionTranslation;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Carbon\Carbon;

class QuestionTranslationModel implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return QuestionTranslation|null
     */
    public function model(array $row)
    {
        return new QuestionTranslation([
           'id'             => $row['id'],
           'question_id'    => $row['question_id'],
           'language_id'    => $row['language_id'],
           'question'       => $row['question'],
           'option_1'       => $row['option_1'],
           'option_2'       => $row['option_2'],
           'option_3'       => $row['option_3'],
           'option_4'       => $row['option_4'],
           'answer'         => $row['answer'],
           'created_at'     => now(),
           'updated_at'     => now(),
        ]);
    }
}
