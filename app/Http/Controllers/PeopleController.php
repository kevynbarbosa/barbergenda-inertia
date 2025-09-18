<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PeopleController extends Controller
{
    public function index(): Response
    {
        $search = request('search');
        $statusFilter = request('status');

        $people = Person::when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ILIKE', "%{$search}%")
                          ->orWhere('document', 'ILIKE', "%{$search}%");
                });
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('people/Index', [
            'people' => $people,
            'filters' => [
                'search' => $search,
                'status' => $statusFilter,
            ],
        ]);
    }
}
