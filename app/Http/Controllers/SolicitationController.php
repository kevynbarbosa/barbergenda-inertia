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

        $solicitations = Solicitation::with(['stage', 'person'])
            ->when($search, function ($query, $search) {
                $query->whereHas('person', function ($query) use ($search) {
                    $query->where('name', 'ILIKE', "%{$search}%")
                          ->orWhere('document', 'ILIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Adiciona informações de SLA para cada solicitação
        $solicitations->getCollection()->transform(function ($solicitation) {
            return $solicitation->append([
                'is_overdue',
                'sla_status',
                'sla_status_color'
            ]);
        });

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
        // Busca ou cria a pessoa
        $person = \App\Models\Person::firstOrCreate(
            ['document' => $request->document],
            ['name' => $request->name]
        );

        // Cria a solicitação
        $solicitation = Solicitation::create([
            'person_id' => $person->id,
            'status' => 'pending',
            'stage_id' => 1, // Primeira etapa (Análise Inicial)
        ]);

        // Calcula a data estimada de conclusão baseada no SLA da etapa
        $solicitation->load('stage');
        $solicitation->calculateEstimatedCompletion();

        return redirect()->route('solicitations.index')
            ->with('success', 'Solicitação criada com sucesso.');
    }

    public function show(Solicitation $solicitation): Response
    {
        $solicitation->load(['stage', 'person']);

        return Inertia::render('solicitations/Show', [
            'solicitation' => $solicitation,
        ]);
    }
}
