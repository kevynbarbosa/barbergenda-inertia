<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitationRequest;
use App\Models\Solicitation;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SolicitationController extends Controller
{
    public function index(): Response
    {
        $search = request('search');

        $solicitations = Solicitation::with('stage')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ILIKE', "%{$search}%")
                          ->orWhere('document', 'ILIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('solicitations/Index', [
            'solicitations' => $solicitations,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('solicitations/Form');
    }

    public function store(StoreSolicitationRequest $request): RedirectResponse
    {
        Solicitation::create([
            'name' => $request->name,
            'document' => $request->document,
            'status' => 'pending',
        ]);

        return redirect()->route('solicitations.index')
            ->with('success', 'Solicitação criada com sucesso.');
    }

    public function show(Solicitation $solicitation): Response
    {
        $solicitation->load('stage');

        return Inertia::render('solicitations/Show', [
            'solicitation' => $solicitation,
        ]);
    }
}
