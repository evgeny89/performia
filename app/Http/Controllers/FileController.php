<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCsvRequest;
use App\Imports\ApartmentImport;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class FileController extends Controller
{
    public function import(UploadCsvRequest $request): bool
    {
        try {
            Excel::import(new ApartmentImport, $request->file('csvFile'));
            return true;
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
