<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class RagDocumentController extends Controller
{
    private function getMitraFolder($rentalId)
    {
        $mapping = [
            1 => 'fz',
            2 => 'putra_wijaya',
            3 => 'aa_rent',
            4 => 'evan_rental',
            5 => 'tng'
        ];
        return $mapping[$rentalId] ?? null;
    }

    public function indexAdmin()
    {
        $docPath = base_path('python_service/dokumen');
        $files = [];
        
        if (File::exists($docPath)) {
            $allFiles = File::files($docPath);
            foreach ($allFiles as $file) {
                if ($file->getExtension() === 'txt') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024, 2) . ' KB',
                        'modified' => date('Y-m-d H:i:s', $file->getMTime())
                    ];
                }
            }
        }
        
        return view('admin.rag.index', compact('files'));
    }

    public function indexMitra()
    {
        $user = Auth::user();
        $rentalId = null;
        if ($user) {
            if (!empty($user->rental_id)) {
                $rentalId = $user->rental_id;
            } else {
                $rental = \App\Models\Rental::where('user_id', $user->id)->first();
                if ($rental) {
                    $rentalId = $rental->id;
                } elseif (!empty($user->branch_id)) {
                    $branch = \App\Models\Branch::find($user->branch_id);
                    $rentalId = $branch ? $branch->rental_id : null;
                }
            }
        }

        $folderName = $this->getMitraFolder($rentalId);
        $files = [];

        if ($folderName) {
            $docPath = base_path('python_service/dokumen/' . $folderName);
            if (File::exists($docPath)) {
                $allFiles = File::files($docPath);
                foreach ($allFiles as $file) {
                    if ($file->getExtension() === 'txt') {
                        $files[] = [
                            'name' => $file->getFilename(),
                            'size' => round($file->getSize() / 1024, 2) . ' KB',
                            'modified' => date('Y-m-d H:i:s', $file->getMTime())
                        ];
                    }
                }
            }
        }
        
        return view('mitra.rag.index', compact('files', 'folderName'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'document' => 'required|file|max:2048',
        ]);

        $file = $request->file('document');
        if (strtolower($file->getClientOriginalExtension()) !== 'txt') {
            return redirect()->back()->with('error', 'Gagal: File harus berformat .txt (Teks Document).');
        }
        $filename = $file->getClientOriginalName();
        $docPath = base_path('python_service/dokumen');
        
        if (!File::exists($docPath)) {
            File::makeDirectory($docPath, 0755, true);
        }

        $file->move($docPath, $filename);

        $this->triggerIngest();

        return redirect()->back()->with('success', 'Dokumen Global berhasil diunggah dan AI telah diperbarui.');
    }

    public function storeMitra(Request $request)
    {
        $request->validate([
            'document' => 'required|file|max:2048',
        ]);

        $file = $request->file('document');
        if (strtolower($file->getClientOriginalExtension()) !== 'txt') {
            return redirect()->back()->with('error', 'Gagal: File harus berformat .txt (Teks Document).');
        }

        $user = Auth::user();
        $rentalId = null;
        if ($user) {
            if (!empty($user->rental_id)) {
                $rentalId = $user->rental_id;
            } else {
                $rental = \App\Models\Rental::where('user_id', $user->id)->first();
                if ($rental) {
                    $rentalId = $rental->id;
                } elseif (!empty($user->branch_id)) {
                    $branch = \App\Models\Branch::find($user->branch_id);
                    $rentalId = $branch ? $branch->rental_id : null;
                }
            }
        }

        $folderName = $this->getMitraFolder($rentalId);

        if (!$folderName) {
            return redirect()->back()->with('error', 'Folder Mitra tidak ditemukan. Pastikan ID Rental Anda valid.');
        }

        $file = $request->file('document');
        $filename = $file->getClientOriginalName();
        $docPath = base_path('python_service/dokumen/' . $folderName);
        
        if (!File::exists($docPath)) {
            File::makeDirectory($docPath, 0755, true);
        }

        $file->move($docPath, $filename);

        $this->triggerIngest();

        return redirect()->back()->with('success', 'Dokumen Kebijakan berhasil diunggah dan AI telah diperbarui.');
    }

    public function destroyAdmin($filename)
    {
        $docPath = base_path('python_service/dokumen/' . $filename);
        if (File::exists($docPath)) {
            File::delete($docPath);
            $this->triggerIngest();
            return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
    }

    public function destroyMitra($filename)
    {
        $user = Auth::user();
        $rentalId = null;
        if ($user) {
            if (!empty($user->rental_id)) {
                $rentalId = $user->rental_id;
            } else {
                $rental = \App\Models\Rental::where('user_id', $user->id)->first();
                if ($rental) {
                    $rentalId = $rental->id;
                } elseif (!empty($user->branch_id)) {
                    $branch = \App\Models\Branch::find($user->branch_id);
                    $rentalId = $branch ? $branch->rental_id : null;
                }
            }
        }
        $folderName = $this->getMitraFolder($rentalId);

        if (!$folderName) {
            return redirect()->back()->with('error', 'Folder Mitra tidak valid.');
        }

        $docPath = base_path('python_service/dokumen/' . $folderName . '/' . $filename);
        if (File::exists($docPath)) {
            File::delete($docPath);
            $this->triggerIngest();
            return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
    }

    private function triggerIngest()
    {
        try {
            $ragBaseUrl = rtrim(env('RAG_ENGINE_URL', 'http://127.0.0.1:5000'), '/');
            Http::timeout(60)->post($ragBaseUrl . '/ingest');
        } catch (\Exception $e) {
            Log::error("Failed to trigger RAG ingest: " . $e->getMessage());
        }
    }
}
