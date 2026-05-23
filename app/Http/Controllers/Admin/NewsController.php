<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\NewsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsRequest;
use App\Models\News;
use App\Services\NewsService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Imagick;

class NewsController extends Controller
{
    protected $newsService;
    protected $uploadService;

    public function __construct(NewsService $newsService, UploadService $uploadService)
    {
        $this->newsService = $newsService;
        $this->uploadService = $uploadService;
    }

    public function index(NewsDataTable $dataTable)
    {
        return $dataTable->render('news.list');
    }

    public function formCreateNews()
    {
        return view('news.form');
    }

    public function formEditNews(String $id)
    {
        $news = News::find($id);

        if (!$news) {
            return redirect()->route('news.index')->with('error', 'News not found.');
        }

        $news->icon_url = $this->uploadService->getPublicUrl($news->icon);

        return view('news.form', compact('news'));
    }

    public function createNews(CreateNewsRequest $request)
    {
        $data = $request->validated();

        $news = $this->newsService->createNews($data['title'], $data['url']);

        return response()->json([
            'status' => true,
            'message' => 'News created successfully.',
            'data' => $news,
        ]);
    }

    public function deleteNewsById(String $id)
    {
        $this->newsService->deleteNewsById($id);

        return response()->json([
            'status' => true,
            'message' => 'News deleted successfully.',
        ]);
    }

    public function updateNews(Request $request, $id)
    {
        $data = $request->all();

        $news = $this->newsService->updateNews($id, $data['title'], $data['url']);

        return response()->json([
            'status' => true,
            'message' => 'News updated successfully.',
            'data' => $news,
        ]);
    }
}
