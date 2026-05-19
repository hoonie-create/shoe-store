<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * 1. Menampilkan produk di halaman HOME USER
     */
    public function index()
    {
        // Mengambil semua produk dari database
        $products = Product::all(); 
        return view('user.home', compact('products'));
    }

    /**
     * 2. Menampilkan halaman MANAJEMEN PRODUK (ADMIN)
     */
    public function indexAdmin()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    /**
     * 3. MEMPROSES PENYIMPANAN produk baru ke database
     * FIX: Mengubah deskripsi menjadi 'required' agar divalidasi oleh Laravel sebelum menyentuh MySQL
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'ukuran'      => 'required',
            'foto'        => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'   => 'required|string', // KUNCI FIXED: Mengubah 'nullable' menjadi 'required'
        ], [
            // Pesan Error Kustom Bahasa Indonesia agar sinkron dengan komponen @error di Blade
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk wajib diisi.',
            'harga.numeric'        => 'Harga produk harus berupa angka.',
            'stok.required'        => 'Jumlah stok gudang wajib diisi.',
            'stok.integer'         => 'Stok harus berupa bilangan bulat.',
            'ukuran.required'      => 'Spesifikasi ukuran sepatu wajib dicantumkan.',
            'foto.required'        => 'Foto master produk wajib diunggah.',
            'foto.image'           => 'Berkas yang diunggah harus berupa gambar.',
            'deskripsi.required'   => 'Deskripsi detail spesifikasi produk wajib isi dan tidak boleh kosong!',
        ]);

        $fotoPath = $request->file('foto')->store('products', 'public');

        Product::create([
            'nama_produk' => $request->nama_produk,
            'harga'       => $request->harga,
            'stok'        => $request->stok,
            'ukuran'      => $request->ukuran,
            'foto'        => $fotoPath,
            'deskripsi'   => $request->deskripsi, // Terjamin tidak akan null saat query INSERT dijalankan
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * 4. Melihat DETAIL PRODUK (USER)
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('user.detail', compact('product'));
    }

    /**
     * 5. Tampilkan halaman edit dengan data produk lama
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products_edit', compact('product'));
    }

    /**
     * 6. Proses pembaruan data ke database
     * FIX: Ditambahkan validasi ketat untuk 'ukuran' dan 'deskripsi' agar tidak memicu query exception saat update
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'nama_produk' => 'required',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'ukuran'      => 'required',
            'deskripsi'   => 'required|string', // KUNCI FIXED: Wajib isi saat update data produk lama
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_produk.required' => 'Nama produk tidak boleh dikosongkan.',
            'harga.required'       => 'Harga jual produk tidak boleh dikosongkan.',
            'stok.required'        => 'Stok unit gudang tidak boleh dikosongkan.',
            'ukuran.required'      => 'Ukuran sepatu wajib dicantumkan.',
            'deskripsi.required'   => 'Deskripsi detail produk wajib diisi.',
        ]);

        // Ambil data yang sudah tervalidasi dengan aman
        $data = $request->only(['nama_produk', 'harga', 'stok', 'ukuran', 'deskripsi']);

        // Jika ada foto baru yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika filenya ada di storage
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * 7. Proses hapus produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus file foto dari storage sebelum record database dihapus
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }
}