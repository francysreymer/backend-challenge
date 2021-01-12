<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MatrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matrix = [
            [1, 2, 3],
            [4, 5, 6],
            [9, 8, 9]            
        ];

        $countRows = 0;
        $primaryDiagonal = $secondaryDiagonal = 0;
        $size = count($matrix) - 1;
        $countColumns = $size;

        while($countRows <= $size) {
            $primaryDiagonal = $primaryDiagonal + $matrix[$countRows][$countRows];
            $secondaryDiagonal = $secondaryDiagonal + $matrix[$countColumns][$countRows];
            $countRows++;
            $countColumns--;
        }

        $result = $primaryDiagonal - $secondaryDiagonal;

        return view('matrix', ['result' => $result]);


        var_dump($primaryDiagonal);
        var_dump($secondaryDiagonal);
        var_dump($result);
    }

}
