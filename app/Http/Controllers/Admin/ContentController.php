<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Mostrar el panel de gestión de contenidos
     */
    public function index()
    {
        $sections = SiteContent::orderBy('section')
            ->orderBy('order')
            ->get()
            ->groupBy('section');

        return view('admin.content.index', compact('sections'));
    }

    /**
     * Actualizar los contenidos
     */
    public function update(Request $request)
    {
        $contents = $request->input('contents', []);

        foreach ($contents as $key => $value) {
            $content = SiteContent::where('key', $key)->first();

            if ($content) {
                // Si es imagen, manejar la subida
                if ($content->type === 'image' && $request->hasFile("contents.{$key}")) {
                    // Eliminar imagen anterior si existe
                    if ($content->value && Storage::disk('public')->exists($content->value)) {
                        Storage::disk('public')->delete($content->value);
                    }
                    $path = $request->file("contents.{$key}")->store('site', 'public');
                    $content->value = $path;
                } else {
                    $content->value = $value;
                }

                $content->save();
            }
        }

        return redirect()->route('admin.content.index')
            ->with('success', 'Contenidos actualizados correctamente');
    }

    /**
     * Restablecer un contenido a su valor por defecto
     */
    public function reset(string $key)
    {
        $content = SiteContent::where('key', $key)->first();

        if ($content) {
            // Si es imagen, eliminar la actual
            if ($content->type === 'image' && $content->value && Storage::disk('public')->exists($content->value)) {
                Storage::disk('public')->delete($content->value);
            }

            $content->value = null;
            $content->save();
        }

        return redirect()->route('admin.content.index')
            ->with('success', 'Contenido restablecido al valor por defecto');
    }
}
