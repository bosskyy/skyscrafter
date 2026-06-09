<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TemplateController extends Controller
{
    /**
     * Display template management page
     */
    public function index()
    {
        $photostripTemplates = $this->getTemplates('templates');
        $keychainTemplates = $this->getTemplates('templates/keychain');
        
        return view('admin.templates.index', compact('photostripTemplates', 'keychainTemplates'));
    }

    /**
     * Get list of templates from directory
     */
    private function getTemplates($path)
    {
        $templates = [];
        $fullPath = public_path($path);
        
        if (File::exists($fullPath)) {
            $files = File::files($fullPath);
            foreach ($files as $file) {
                if (in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $templates[] = [
                        'filename' => $file->getFilename(),
                        'path' => $path . '/' . $file->getFilename(),
                        'url' => asset($path . '/' . $file->getFilename()),
                        'size' => File::size($fullPath . '/' . $file->getFilename()),
                        'created_at' => date('d M Y H:i', $file->getMTime())
                    ];
                }
            }
        }
        
        return $templates;
    }

    /**
     * Upload new template
     */
    public function upload(Request $request)
    {
        $request->validate([
            'template_type' => 'required|in:photostrip,keychain',
            'template_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $type = $request->template_type;
        $basePath = $type === 'keychain' ? 'templates/keychain' : 'templates';
        $fullPath = public_path($basePath);

        // Ensure directory exists
        if (!File::exists($fullPath)) {
            File::makeDirectory($fullPath, 0755, true);
        }

        // Store file
        $file = $request->file('template_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($fullPath, $filename);

        $message = $type === 'keychain' 
            ? 'Template Gantungan Kunci berhasil ditambahkan!' 
            : 'Template Photostrip berhasil ditambahkan!';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete template
     */
    public function delete(Request $request)
    {
        $request->validate([
            'template_type' => 'required|in:photostrip,keychain',
            'filename' => 'required|string'
        ]);

        $type = $request->template_type;
        $basePath = $type === 'keychain' ? 'templates/keychain' : 'templates';
        $fullPath = public_path($basePath . '/' . $request->filename);

        // Security check - ensure the file is in the correct directory
        if (str_starts_with(realpath($fullPath), realpath(public_path($basePath))) && File::exists($fullPath)) {
            File::delete($fullPath);
            
            $message = $type === 'keychain' 
                ? 'Template Gantungan Kunci berhasil dihapus!' 
                : 'Template Photostrip berhasil dihapus!';
            
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan atau tidak valid!');
    }
}
