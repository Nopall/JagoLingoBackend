<?php

namespace App\DataTables;

use App\Models\Lesson;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class PhaseLessonDataTable extends DataTable
{
    protected $uploadService;
    protected $id;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'phase.phaselesson-action')
            ->addColumn('audio', function ($row) {
                // Menampilkan ikon audio jika ada audio_url
                if ($row->audio_url) {
                    return '<a href="' . $row->audio_url . '" target="_blank">
                                <i class="bx bx-volume-full"></i> <!-- Ikon suara dari Boxicons -->
                            </a>';
                }
                return '<i class="bx bx-volume-mute"></i>'; // Jika tidak ada audio
            })
            ->addColumn('image', function ($row) {
                // Menampilkan thumbnail gambar jika ada image_url
                if ($row->image_url) {
                    return '<img src="' . $this->uploadService->getPublicUrl($row->image_url) . '" alt="Image" style="width:50px; height:auto;">';
                }
                return '<i class="bx bx-image"></i>'; // Jika tidak ada gambar
            })
            ->rawColumns(['action', 'audio', 'image']); // Menandakan bahwa kolom audio dan image menggunakan HTML
    }

    public function query(Lesson $model)
    {
        return $model->newQuery()->where('phase_id', $this->id)->select('lessons.*');
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}

