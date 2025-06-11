<?php

namespace App\Exports;

use App\Models\Task;

//Da error las clases importadas cuando se usan en implements
/* use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable; */
class TasksExport
{


    protected string $search;
    protected string $filter;

    public function __construct(string $search, string $filter)
    {
        $this->search = $search;
        $this->filter = $filter;
    }

    public function query()
    {
        $tasksQuery = Task::query()->with('category');

        if ($this->filter === 'pending') {
            $tasksQuery->where('is_completed', false);
        } elseif ($this->filter === 'completed') {
            $tasksQuery->where('is_completed', true);
        }

        if (!empty($this->search)) {
            $tasksQuery->where('title', 'like', '%' . $this->search . '%');
        }

        return $tasksQuery->latest();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Category',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    /**
    * @param mixed $task
    * @return array
    */
    public function map($task): array
    {
        return [
            $task->id,
            $task->title,
            $task->category ? $task->category->name : 'N/A',
            $task->is_completed ? 'Completed' : 'Pending',
            $task->created_at->format('Y-m-d H:i:s'),
            $task->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}