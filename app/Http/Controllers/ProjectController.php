<?php

namespace App\Http\Controllers;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->middleware('auth');
    }

    public function index()
    {
        $projects = Project::withCount(['tasks' => function ($query) {
            if (!auth()->user()->is_admin) {
                $query->where('user_id', auth()->id());
            }
        }])->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $validated['user_id'] = auth()->id();

        $this->projectRepository->create($validated);
        return redirect()->route('projects.index')->with('success', 'Проект создан');
    }

    /**
     * @throws AuthorizationException
     */
    public function edit($id)
    {
        $project = $this->projectRepository->find($id);
        return view('projects.edit', compact('project'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Request $request, int $id): RedirectResponse
    {

        try {
            $project = Project::findOrFail($id);
            $this->authorize('update', $project);

            $validated = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable'
            ]);

            $this->projectRepository->update($id, $validated);
            return redirect()->route('projects.index')->with('success', 'Проект обновлен');
        } catch (AuthorizationException $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Ошибка: Вы не можете редактировать чужой проект');
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $project = Project::findOrFail($id);
            $this->authorize('delete', $project);

            $project->delete();

            return redirect()->route('projects.index')
                ->with('success', 'Проект успешно удален');

        } catch (AuthorizationException $e) {
            return redirect()->route('projects.index')
                ->with('error', 'Ошибка: Вы не можете удалить чужой проект');
        }
    }
}
