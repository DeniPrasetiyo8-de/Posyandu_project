<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleApiController extends Controller
{
    private $articles = array();
    
    public function __construct()
    {
        $this->articles = array(
            array(
                'id' => 1,
                'title' => 'Tahapan Gizi Bayi yang Tepat',
                'category' => 'gizi_anak',
                'image_url' => asset('images/G Tahapan gizi bayi.png'),
                'excerpt' => 'Pemantauan pertumbuhan dan perkembangan bayi sangat penting...',
                'content' => 'Pemantauan pertumbuhan dan perkembangan bayi merupakan hal yang sangat penting...',
                'created_at' => '2024-10-15'
            ),
            array(
                'id' => 2,
                'title' => 'Gizi Seimbang untuk Keluarga',
                'category' => 'gizi_anak',
                'image_url' => asset('images/G Gizi seimbang.jpg'),
                'excerpt' => 'Gizi seimbang adalah susunan makanan harian...',
                'content' => 'Gizi seimbang adalah susunan makanan harian yang mengandung...',
                'created_at' => '2024-10-12'
            ),
            array(
                'id' => 3,
                'title' => 'Jadwal Imunisasi Anak Lengkap',
                'category' => 'imunisasi',
                'image_url' => asset('images/G Imunisasi anak.png'),
                'excerpt' => 'Imunisasi merupakan upaya perlindungan tubuh...',
                'content' => 'Imunisasi merupakan upaya perlindungan tubuh untuk kebal terhadap...',
                'created_at' => '2024-10-10'
            ),
        );
    }

    public function index(Request $request)
    {
        $articles = $this->articles;
        
        if ($request->has('category') && $request->category) {
            $articles = array_filter($articles, function($a) use ($request) {
                return $a['category'] === $request->category;
            });
        }
        
        $data = array_map(function($article) {
            return array(
                'id' => $article['id'],
                'title' => $article['title'],
                'category' => $article['category'],
                'image_url' => $article['image_url'],
                'excerpt' => substr($article['content'], 0, 150),
                'created_at' => $article['created_at'],
            );
        }, $articles);
        
        return response()->json(array(
            'success' => true,
            'data' => array_values($data)
        ));
    }

    public function show($id)
    {
        $article = null;
        foreach ($this->articles as $a) {
            if ($a['id'] == $id) {
                $article = $a;
                break;
            }
        }
        
        if (!$article) {
            return response()->json(array(
                'success' => false,
                'message' => 'Artikel tidak ditemukan'
            ), 404);
        }
        
        return response()->json(array(
            'success' => true,
            'data' => $article
        ));
    }

    public function store(Request $request)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        $validated = $request->validate(array(
            'title' => 'required|string|max:255',
            'category' => 'required|in:gizi_anak,ibu_hamil,imunisasi',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ));
        
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $imageUrl = asset('storage/' . $path);
        }
        
        $newId = count($this->articles) + 1;
        
        return response()->json(array(
            'success' => true,
            'message' => 'Artikel berhasil ditambahkan',
            'data' => array(
                'id' => $newId,
                'title' => $validated['title'],
                'image_url' => $imageUrl,
            )
        ), 201);
    }

    public function update(Request $request, $id)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        $validated = $request->validate(array(
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|in:gizi_anak,ibu_hamil,imunisasi',
            'content' => 'sometimes|string',
        ));
        
        return response()->json(array(
            'success' => true,
            'message' => 'Artikel berhasil diperbarui'
        ));
    }

    public function destroy(Request $request, $id)
    {
        if (!$this->isAdmin($request)) {
            return response()->json(array('success' => false, 'message' => 'Akses ditolak'), 403);
        }
        
        return response()->json(array(
            'success' => true,
            'message' => 'Artikel berhasil dihapus'
        ));
    }
    
    private function isAdmin(Request $request)
    {
        return $request->user() && in_array($request->user()->role, array('admin', 'admin_posyandu'));
    }
}
