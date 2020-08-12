<?php

namespace App\Imports;

use App\User;

use Maatwebsite\Excel\Concerns\ToModel;

class QuestionTranslation implements ToModel
{
    /**
     * @param array $row
     *
     * @return QuestionTranslation|null
     */
    public function model(array $row)
    {
        return new QuestionTranslation([
           'id'             => $row[0],
           'question_id'    => $row[1],
           'language_id'    => $row[2],
           'question'       => $row[3],
           'option_1'       => $row[4],
           'option_2'       => $row[5],
           'option_3'       => $row[6],
           'option_4'       => $row[7],
           'answer'         => $row[8],
           'created_at'     => $row[9],
           'updated_at'     => $row[10],
        ]);
    }
}
