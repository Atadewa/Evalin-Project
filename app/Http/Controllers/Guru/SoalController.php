<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SoalImport;
use App\Models\Guru;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\OpsiJawaban;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class SoalController extends Controller
{
    public function index()
    {
        $soals = Soal::with('ujian')->latest()->paginate(10);
        return view('guru.soal.index', compact('soals'));
    }

    public function create(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $ujian_id = $request->get('ujian_id');

        if ($ujian_id) {
            // Validate ujian belongs to current guru
            $ujian = Ujian::where('created_by', $guru->id)->findOrFail($ujian_id);
            $ujians = collect([$ujian]); // Only show the selected ujian
        } else {
            $ujians = Ujian::where('created_by', $guru->id)->get();
            $ujian = null;
        }

        return view('guru.soal.create', compact('ujians', 'ujian'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ujian_id' => 'required|exists:ujian,id',
            'pertanyaan' => 'required|string',
            'jenis_soal' => 'required|in:pilgan,essay',
            'pembahasan' => 'required|string',
            'jawaban_benar' => 'required',
        ], [
            'ujian_id.required' => 'Ujian harus dipilih.',
            'ujian_id.exists' => 'Ujian yang dipilih tidak valid.',
            'pertanyaan.required' => 'Pertanyaan harus diisi.',
            'jenis_soal.required' => 'Jenis soal harus dipilih.',
            'pembahasan.required' => 'Pembahasan harus diisi.',
            'jawaban_benar.required' => 'Kunci jawaban harus diisi untuk soal essay.',
        ]);
        // Validasi khusus untuk pilihan ganda
        if ($request->jenis_soal === 'pilgan') {
            $validator->after(function ($validator) use ($request) {
                $opsiCount = 0;
                for ($i = 1; $i <= 5; $i++) {
                    if ($request->has("opsi_$i") && !empty($request->input("opsi_$i"))) {
                        $opsiCount++;
                    }
                }

                if ($opsiCount < 2) {
                    $validator->errors()->add('opsi', 'Minimal harus ada 2 opsi jawaban.');
                }

                if (!$request->has('jawaban_benar') || empty($request->jawaban_benar)) {
                    $validator->errors()->add('jawaban_benar', 'Jawaban benar harus dipilih.');
                }
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the question
        $soal = Soal::create([
            'ujian_id' => $request->ujian_id,
            'pertanyaan' => $request->pertanyaan,
            'tipe_soal' => $request->jenis_soal,
            'jawaban_benar' => $request->jawaban_benar,
            'pembahasan' => $request->pembahasan,
        ]);

        // Handle multiple choice options
        if ($request->jenis_soal === 'pilgan') {
            for ($i = 1; $i <= 5; $i++) {
                $opsiText = $request->input("opsi_$i");
                if (!empty($opsiText)) {
                    $label = chr(64 + $i); // A, B, C, D, E
                    OpsiJawaban::create([
                        'soal_id' => $soal->id,
                        'label' => $label,
                        'isi_opsi' => $opsiText,
                        'is_correct' => $request->jawaban_benar == $label, // Compare letter to letter
                    ]);
                }
            }
        }

        $action = $request->input('action', 'save');

        if ($action === 'save_and_add') {
            return redirect()->route('guru.soal.create', ['ujian_id' => $request->ujian_id])
                ->with('success', 'Soal berhasil ditambahkan. Silakan tambah soal lainnya.');
        }

        return redirect()->route('guru.ujian.show', $soal->ujian_id)
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function show(Soal $soal)
    {
        //
    }

    public function edit(Soal $soal)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $ujians = Ujian::where('created_by', $guru->id)->get();
        return view('guru.soal.edit', compact('soal', 'ujians'));
    }

    public function update(Request $request, Soal $soal)
    {
        $validator = Validator::make($request->all(), [
            'nomor_soal' => 'required|numeric|min:1',
            'pertanyaan' => 'required|string',
            'skor' => 'required|numeric|min:1|max:100',
            'jawaban_benar' => 'required_if:jenis_soal,essay|string',
        ], [
            'nomor_soal.required' => 'Nomor soal harus diisi.',
            'nomor_soal.numeric' => 'Nomor soal harus berupa angka.',
            'pertanyaan.required' => 'Pertanyaan harus diisi.',
            'skor.required' => 'Skor harus diisi.',
            'skor.numeric' => 'Skor harus berupa angka.',
            'skor.min' => 'Skor minimal adalah 1.',
            'skor.max' => 'Skor maksimal adalah 100.',
            'jawaban_benar.required_if' => 'Kunci jawaban harus diisi untuk soal essay.',
        ]);

        // Validasi khusus untuk pilihan ganda
        if ($soal->jenis_soal === 'pilgan') {
            $validator->after(function ($validator) use ($request) {
                $opsiCount = 0;
                for ($i = 1; $i <= 5; $i++) {
                    if ($request->has("opsi_$i") && !empty($request->input("opsi_$i"))) {
                        $opsiCount++;
                    }
                }

                if ($opsiCount < 2) {
                    $validator->errors()->add('opsi', 'Minimal harus ada 2 opsi jawaban.');
                }

                if (!$request->has('jawaban_benar') || empty($request->jawaban_benar)) {
                    $validator->errors()->add('jawaban_benar', 'Jawaban benar harus dipilih.');
                }
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the question
        $soal->update([
            'nomor_soal' => $request->nomor_soal,
            'pertanyaan' => $request->pertanyaan,
            'skor' => $request->skor,
            'jawaban_benar' => $soal->jenis_soal === 'essay' ? $request->jawaban_benar : null,
        ]);

        // Handle multiple choice options update
        if ($soal->jenis_soal === 'pilgan') {
            // Delete existing options
            $soal->opsiJawaban()->delete();

            // Create new options
            for ($i = 1; $i <= 5; $i++) {
                $opsiText = $request->input("opsi_$i");
                if (!empty($opsiText)) {
                    $label = chr(64 + $i); // A, B, C, D, E
                    OpsiJawaban::create([
                        'soal_id' => $soal->id,
                        'label' => $label,
                        'isi_opsi' => $opsiText,
                        'is_correct' => $request->jawaban_benar == $i,
                    ]);
                }
            }
        }

        return redirect()->route('guru.ujian.show', $soal->ujian_id)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $soal->delete();

        return back()->with('success', 'Soal deleted successfully.');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toCollection(null, $file);
            $rows = $data[0];

            DB::beginTransaction();

            // Statistik import
            $imported = 0;
            $skipped = 0;
            $skippedRows = [];

            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // skip header

                $ujianId = $row[0];
                $pertanyaan = $row[1];
                $tipeSoal = strtolower(trim($row[2]));
                $jawabanBenar = $row[7] ?? null;
                $pembahasan = $row[8] ?? null;

                if (!$ujianId || !$pertanyaan || !$tipeSoal) continue;

                $ujian = Ujian::find($ujianId);
                if (!$ujian) {
                    $skipped++;
                    $skippedRows[] = "Baris " . ($index + 1) . ": ujian_id tidak valid";
                    continue;
                }

                $jenisUjian = strtolower($ujian->jenis_ujian);

                // ✅ Cek kesesuaian tipe
                if ($jenisUjian !== 'campuran' && $jenisUjian !== $tipeSoal) {
                    $skipped++;
                    $skippedRows[] = "Baris " . ($index + 1) . ": tipe soal '{$tipeSoal}' tidak sesuai dengan tipe ujian '{$jenisUjian}'";
                    continue;
                }

                // Buat soal
                $soal = Soal::create([
                    'ujian_id' => $ujianId,
                    'pertanyaan' => $pertanyaan,
                    'tipe_soal' => $tipeSoal,
                    'jawaban_benar' => $tipeSoal === 'essay' ? $jawabanBenar : null,
                    'pembahasan' => $pembahasan,
                ]);

                // Kalau tipe pilgan → masukkan opsi jawaban
                if ($tipeSoal === 'pilgan') {
                    $opsi = [
                        'A' => $row[3] ?? null,
                        'B' => $row[4] ?? null,
                        'C' => $row[5] ?? null,
                        'D' => $row[6] ?? null,
                    ];

                    foreach ($opsi as $label => $isi) {
                        if ($isi) {
                            OpsiJawaban::create([
                                'soal_id' => $soal->id,
                                'label' => $label,
                                'isi_opsi' => $isi,
                                'is_correct' => strtoupper(trim($jawabanBenar)) === $label ? 1 : 0,
                            ]);
                        }
                    }
                }

                $imported++;
            }

            DB::commit();

            // Buat pesan hasil
            $message = "<strong>{$imported}</strong> soal berhasil diimport.";
            if ($skipped > 0) {
                $message .= " <br><strong>{$skipped}</strong> soal dilewati karena kesalahan:<ul class='list-disc pl-5 mt-2 text-sm text-gray-700'>";
                foreach ($skippedRows as $msg) {
                    $message .= "<li>{$msg}</li>";
                }
                $message .= "</ul>";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
