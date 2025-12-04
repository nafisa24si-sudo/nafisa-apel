<?php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\PelangganAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterableColums = [ 'gender'];
        $searchableColumns = ['first_name']; //sesuai kolom Pelanggan
        $data['dataPelanggan'] = Pelanggan::filter($request, $filterableColums)
        ->search($request, $searchableColumns)
        ->paginate(10)->withQueryString();
        
        return view('admin.pelanggan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:pelanggan',
            'phone' => 'nullable|string|max:20',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
        ]);

        $pelanggan = Pelanggan::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birthday' => $validated['birthday'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Handle multiple file upload
        if ($request->hasFile('attachments')) {
            $this->storeAttachments($pelanggan, $request->file('attachments'));
        }

        return redirect()->route('pelanggan.index')->with('success', 'Penambahan Data Berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dataPelanggan = Pelanggan::findOrFail($id);
        return view('admin.pelanggan.edit_new', compact('dataPelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:pelanggan,email,' . $id . ',pelanggan_id',
            'phone' => 'nullable|string|max:20',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
            'delete_attachment_ids' => 'nullable|string',
        ]);

        // Update data
        $pelanggan->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birthday' => $validated['birthday'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Delete selected attachments
        if ($request->filled('delete_attachment_ids')) {
            $deleteIds = explode(',', $request->delete_attachment_ids);
            foreach ($deleteIds as $deleteId) {
                $attachment = PelangganAttachment::find(trim($deleteId));
                if ($attachment) {
                    if (Storage::exists('public/pelanggan_files/' . $attachment->file_path)) {
                        Storage::delete('public/pelanggan_files/' . $attachment->file_path);
                    }
                    $attachment->delete();
                }
            }
        }

        // Handle new file uploads
        if ($request->hasFile('attachments')) {
            $this->storeAttachments($pelanggan, $request->file('attachments'));
        }

        return redirect()->route('pelanggan.index')->with('update', 'Perubahan Data Berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Delete all attachments
        foreach ($pelanggan->attachments as $attachment) {
            if (Storage::exists('public/pelanggan_files/' . $attachment->file_path)) {
                Storage::delete('public/pelanggan_files/' . $attachment->file_path);
            }
            $attachment->delete();
        }

        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('delete', 'Data berhasil dihapus');
    }

    /**
     * Store attachments for a pelanggan
     */
    private function storeAttachments(Pelanggan $pelanggan, $files)
    {
        foreach ($files as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/pelanggan_files', $filename);

            PelangganAttachment::create([
                'pelanggan_id' => $pelanggan->pelanggan_id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filename,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }
}
