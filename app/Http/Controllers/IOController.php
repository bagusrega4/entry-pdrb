<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IoDataset;
use App\Models\IoTransaction;
use App\Models\IoFinalDemand;
use App\Models\IoOutput;
use App\Models\Commodity;

class IOController extends Controller
{
    public function index()
    {
        $datasets = IoDataset::latest()->get();
        return view('io.index', compact('datasets'));
    }

    public function create()
    {
        return view('io.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dataset'   => 'required|string|max:255',
            'tahun'          => 'required|digits:4',
            'jumlah_sektor'  => 'required|integer|min:1|max:50'
        ]);

        IoDataset::create([
            'nama_dataset'  => $request->nama_dataset,
            'tahun'         => $request->tahun,
            'jumlah_sektor' => $request->jumlah_sektor
        ]);

        return redirect()->route('io.index')
            ->with('success', 'Dataset IO berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $dataset = IoDataset::findOrFail($id);
        return view('io.edit', compact('dataset'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_dataset' => 'required|string|max:255',
            'tahun'        => 'required|digits:4',
        ]);

        $dataset = IoDataset::findOrFail($id);

        $dataset->update([
            'nama_dataset' => $request->nama_dataset,
            'tahun'        => $request->tahun,
        ]);

        return redirect()->route('io.index')
            ->with('success', 'Dataset IO berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dataset = IoDataset::findOrFail($id);
        $dataset->delete();

        return redirect()->route('io.index')
            ->with('success', 'Dataset IO berhasil dihapus!');
    }

    public function input($id)
    {
        $dataset = IoDataset::findOrFail($id);

        $transactions = IoTransaction::where('dataset_id', $id)->get();
        $finalDemands = IoFinalDemand::where('dataset_id', $id)->get();
        $outputs      = IoOutput::where('dataset_id', $id)->get();

        $sektors = Commodity::whereNull('parent_id')
            ->orderBy('id')
            ->take($dataset->jumlah_sektor)
            ->get();

        $z = [];
        foreach ($transactions as $t) {
            $z[$t->baris][$t->kolom] = $t->nilai;
        }

        $fd = [];
        foreach ($finalDemands as $f) {
            $fd[$f->sektor] = $f->nilai;
        }

        $out = [];
        foreach ($outputs as $o) {
            $out[$o->sektor] = $o->nilai;
        }

        return view('io.input', compact(
            'dataset',
            'z',
            'fd',
            'out',
            'sektors'
        ));
    }

    public function storeMatrix(Request $request, $id)
    {
        $dataset = IoDataset::findOrFail($id);

        if ($request->has('z')) {
            foreach ($request->z as $i => $row) {
                foreach ($row as $j => $value) {
                    IoTransaction::updateOrCreate(
                        [
                            'dataset_id' => $dataset->id,
                            'baris'      => $i,
                            'kolom'      => $j
                        ],
                        [
                            'nilai' => $value ?? 0
                        ]
                    );
                }
            }
        }

        if ($request->has('fd')) {
            foreach ($request->fd as $i => $value) {
                IoFinalDemand::updateOrCreate(
                    [
                        'dataset_id' => $dataset->id,
                        'sektor'     => $i
                    ],
                    [
                        'nilai' => $value ?? 0
                    ]
                );
            }
        }

        if ($request->has('output')) {
            foreach ($request->output as $i => $value) {
                IoOutput::updateOrCreate(
                    [
                        'dataset_id' => $dataset->id,
                        'sektor'     => $i
                    ],
                    [
                        'nilai' => $value ?? 0
                    ]
                );
            }
        }

        return redirect()->route('io.index')
            ->with('success', 'Tabel IO berhasil disimpan!');
    }

    public function matrixA($id)
    {
        $dataset = IoDataset::findOrFail($id);

        $transactions = IoTransaction::where('dataset_id', $id)->get();
        $outputs      = IoOutput::where('dataset_id', $id)->get();

        // MASTER SEKTOR
        $sektors = Commodity::whereNull('parent_id')
            ->orderBy('id')
            ->take($dataset->jumlah_sektor)
            ->get();

        // Z matrix
        $z = [];
        foreach ($transactions as $t) {
            $z[$t->baris][$t->kolom] = $t->nilai;
        }

        // Output vector
        $x = [];
        foreach ($outputs as $o) {
            $x[$o->sektor] = $o->nilai;
        }

        $n = $dataset->jumlah_sektor;

        // MATRIX A
        $A = [];

        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {

                $zij = $z[$i][$j] ?? 0;
                $xj  = $x[$j] ?? 0;

                $A[$i][$j] = ($xj != 0) ? ($zij / $xj) : 0;
            }
        }

        return view('io.matrix-a', compact(
            'dataset',
            'A',
            'sektors'
        ));
    }

    public function leontief($id)
    {
        $dataset = IoDataset::findOrFail($id);

        $transactions = IoTransaction::where('dataset_id', $id)->get();
        $outputs      = IoOutput::where('dataset_id', $id)->get();

        // MASTER SEKTOR
        $sektors = Commodity::whereNull('parent_id')
            ->orderBy('id')
            ->take($dataset->jumlah_sektor)
            ->get();

        $n = $dataset->jumlah_sektor;

        // Z
        $z = [];
        foreach ($transactions as $t) {
            $z[$t->baris][$t->kolom] = $t->nilai;
        }

        // X
        $x = [];
        foreach ($outputs as $o) {
            $x[$o->sektor] = $o->nilai;
        }

        // MATRIX A
        $A = [];
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {

                $zij = $z[$i][$j] ?? 0;
                $xj  = $x[$j] ?? 0;

                $A[$i][$j] = ($xj != 0) ? ($zij / $xj) : 0;
            }
        }

        // I - A
        $B = [];
        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {

                $B[$i][$j] = ($i == $j)
                    ? (1 - $A[$i][$j])
                    : (-$A[$i][$j]);
            }
        }

        $inverse = $this->matrixInverse($B, $n);

        return view('io.leontief', compact(
            'dataset',
            'inverse',
            'sektors'
        ));
    }

    private function matrixInverse($matrix, $n)
    {
        $identity = [];

        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $identity[$i][$j] = ($i == $j) ? 1 : 0;
            }
        }

        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $matrix[$i][$j + $n] = $identity[$i][$j];
            }
        }

        for ($i = 1; $i <= $n; $i++) {

            $pivot = $matrix[$i][$i];

            if ($pivot == 0) {
                return null;
            }

            for ($j = 1; $j <= 2 * $n; $j++) {
                $matrix[$i][$j] /= $pivot;
            }

            for ($k = 1; $k <= $n; $k++) {

                if ($k == $i) continue;

                $factor = $matrix[$k][$i];

                for ($j = 1; $j <= 2 * $n; $j++) {
                    $matrix[$k][$j] -= $factor * $matrix[$i][$j];
                }
            }
        }

        $inverse = [];

        for ($i = 1; $i <= $n; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $inverse[$i][$j] = $matrix[$i][$j + $n];
            }
        }

        return $inverse;
    }
}