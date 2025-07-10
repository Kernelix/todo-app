<?php

namespace App\Http\Controllers;

use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskRepositoryInterface $taskRepository;
    protected ProjectRepositoryInterface $projectRepository;

    public function __construct(
        TaskRepositoryInterface    $taskRepository,
        ProjectRepositoryInterface $projectRepository
    )
    {
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
        $this->middleware('auth');
    }

    public function index()
    {
        $status = request('status', 'all');
        $search = request('search');


        $tasks = Task::query()
            ->when(!auth()->user()->is_admin, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($status === 'active', fn($q) => $q->where('status', '!=', 'completed'))
            ->when($status === 'completed', fn($q) => $q->where('status', 'completed'))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->with('project')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $projects = $this->projectRepository->all();
        $statuses = [
            'pending' => __('task.status.pending'),
            'in_progress' => __('task.status.in_progress'),
            'testing' => __('task.status.testing'),
            'review' => __('task.status.review'),
            'completed' => __('task.status.completed')
        ];

        return view('tasks.create', compact('projects', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'required|in:pending,in_progress,testing,review,completed',
            'project_id' => 'required|exists:projects,id'
        ]);

        $validated['user_id'] = auth()->id();

        $this->taskRepository->create($validated);
        return redirect()->route('tasks.index')->with('success', 'Задача создана');
    }


    public function edit(int $id)
    {
        $task = $this->taskRepository->find($id);


        $projects = $this->projectRepository->all();
        $statuses = [
            'pending' => __('task.status.pending'),
            'in_progress' => __('task.status.in_progress'),
            'testing' => __('task.status.testing'),
            'review' => __('task.status.review'),
            'completed' => __('task.status.completed')
        ];

        return view('tasks.edit', compact('task', 'projects', 'statuses'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'required|in:pending,in_progress,testing,review,completed',
            'project_id' => 'required|exists:projects,id'
        ]);

        $this->taskRepository->update($id, $validated);
        return redirect()->route('tasks.index')->with('success', 'Задача обновлена');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy($id): RedirectResponse
    {
        $task = Task::findOrFail($id);

        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Задача удалена');
    }

    public function filter($status)
    {
        $tasks = $this->taskRepository->filter($status);
        return view('tasks.index', [
            'tasks' => $tasks,
            'currentStatus' => $status
        ]);
    }
}
