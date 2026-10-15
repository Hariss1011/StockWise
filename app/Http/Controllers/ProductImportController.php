<?php

namespace App\Http\Controllers;

use App\Exports\ProductTemplateExport;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ProductImportController extends Controller
{
    public function create()
    {
        return view('products.import');
    }

    public function template()
    {
        return Excel::download(
            new ProductTemplateExport(),
            'template-impor-produk-stockwise.xlsx'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx',
                'max:5120',
            ],
        ], [
            'file.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file.mimes' => 'File harus berformat Excel .xlsx.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $import = new ProductsImport();

        try {
            Excel::import($import, $request->file('file'));

            return redirect()
                ->route('products.index')
                ->with(
                    'success',
                    $import->importedCount . ' produk berhasil diimpor.'
                );
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'file' => 'Terjadi kesalahan saat membaca atau menyimpan file. Periksa format Excel dan coba kembali.',
            ]);
        }
    }
}
