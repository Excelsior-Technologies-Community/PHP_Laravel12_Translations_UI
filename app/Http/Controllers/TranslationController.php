<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Translation;

class TranslationController extends Controller
{
    private $locales = ['en', 'fr', 'es'];

    public function index()
    {
        $translations = Translation::all()
            ->groupBy('key');

        return view('translations.index', [
            'translations' => $translations,
            'locales' => $this->locales
        ]);
    }

    public function store(Request $request)
    {
        foreach ($request->translations as $key => $locales) {

            foreach ($locales as $locale => $value) {

                Translation::updateOrCreate(
                    [
                        'key' => $key,
                        'locale' => $locale,
                        'group' => 'messages'
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }

        return back()->with('success', 'Translations saved successfully');
    }

    public function addKey(Request $request)
    {
        foreach ($this->locales as $locale) {

            Translation::create([
                'locale' => $locale,
                'group' => 'messages',
                'key' => $request->key,
                'value' => ''
            ]);
        }

        return back();
    }
}