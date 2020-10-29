<?php

namespace App\Controllers;

use App\Models\ComicsModel;
use CodeIgniter\Validation\Rules;

class Comics extends BaseController
{
    protected $comicsModel;
    public function __construct()
    {
        $this->comicsModel = new ComicsModel();
    }

    public function index()
    {
        // $comics = $this->comicsModel->findAll();

        $data = [
            'judul' => 'Daftar Komik',
            'comics' => $this->comicsModel->getComics()
        ];

        // cara konek db tanpa model
        // $db = \Config\Database::connect();
        // $comics = $db->query("SELECT*FROM comics");
        // foreach ($comics->getResultArray() as $row) {
        //     d($row);
        // }
        return view('comics/index', $data);
    }

    public function detail($slug)
    {

        $data = [
            'judul' => 'Detail Komik',
            'comics' => $this->comicsModel->getComics($slug)
        ];

        if (empty($data['comics'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul Komik ' . $slug . ' tidak ditemukan');
        }
        return view('comics/detail', $data);
    }

    public function create()
    {
        $data = [
            'judul' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];
        return view('comics/create', $data);
    }

    public function save()
    {
        //validasi input

        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[comics.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} tersebut sudah terdaftar'
                ]
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,2048]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'ukuran gambar terlalu besar',
                    'is_image' => 'file yang anda kirim bukan gambar',
                    'mime_in' => 'ekstensi gambar tidak didukung'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/comics/create')->withInput()->with('validation', $validation);
            return redirect()->to('/comics/create')->withInput();
        }
        //ambil gambar
        $fileSampul = $this->request->getFile('sampul');
        // apakah tidak ada gambar yang di upload 
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.jpg';
        } else {
            //generate nama random sampul
            $namaSampul = $fileSampul->getRandomName();
            //pindahkan gambar
            $fileSampul->move('img', $namaSampul);
        }
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->comicsModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil Ditambahkan');

        return redirect()->to('/comics');
    }

    public function delete($id)
    {
        //cari gambar berdasarkan id
        $comics = $this->comicsModel->find($id);
        //cek jika file gambarnya default.jpg
        if ($comics['sampul'] != 'default.jpg') {
            //hapus gambar
            unlink('img/' . $comics['sampul']);
        }
        $this->comicsModel->delete($id);
        session()->setFlashdata('pesan', 'Data Berhasil Dihapus');
        return redirect()->to('/comics');
    }

    public function edit($slug)
    {
        $data = [
            'judul' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'comics' => $this->comicsModel->getComics($slug)
        ];
        return view('comics/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[comics.judul,id, ' . $id . ']',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} tersebut sudah terdaftar'
                ],
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,2048]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'ukuran gambar terlalu besar',
                    'is_image' => 'file yang anda kirim bukan gambar',
                    'mime_in' => 'ekstensi gambar tidak didukung'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            return redirect()->to('/comics/edit/' . $this->request->getVar('slug'))->withInput();
        }
        $fileSampul = $this->request->getFile('sampul');
        //cek gambar apakah tetap gambar lama
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            //generate random name
            $namaSampul = $fileSampul->getRandomName();
            //pindahkan / upload gambar
            $fileSampul->move('img', $namaSampul);
            //hapus file yang lama
            unlink('img/' . $this->request->getVar('sampulLama'));
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->comicsModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil Diubah');

        return redirect()->to('/comics');
    }
}
