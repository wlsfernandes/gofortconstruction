<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveFaqRequest;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FaqController extends Controller
{
    private const PER_PAGE = 25;

    private const STATUS_QUESTION_LENGTH = 60;

    public function index(): View
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::orderBy('sort_order')->orderBy('id')->paginate(self::PER_PAGE),
        ]);
    }

    public function create(): View
    {
        return view('admin.faqs.form', ['faq' => new Faq]);
    }

    public function store(SaveFaqRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        // A blank order appends the question after the current last one.
        $attributes['sort_order'] ??= (Faq::max('sort_order') ?? 0) + 1;

        $faq = Faq::create($attributes);

        return redirect()->route('admin.faqs.index')->with('status', 'Added "'.Str::limit($faq->question, self::STATUS_QUESTION_LENGTH).'".');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', ['faq' => $faq]);
    }

    public function update(SaveFaqRequest $request, Faq $faq): RedirectResponse
    {
        $attributes = $request->validated();
        // A blank order on edit keeps the question where it is.
        $attributes['sort_order'] ??= $faq->sort_order;

        $faq->update($attributes);

        return redirect()->route('admin.faqs.index')->with('status', 'Updated "'.Str::limit($faq->question, self::STATUS_QUESTION_LENGTH).'".');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('status', 'Deleted "'.Str::limit($faq->question, self::STATUS_QUESTION_LENGTH).'".');
    }
}
