<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data PDRB {{ $triwulan->triwulan }} - {{ $tahun }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 7pt;
            line-height: 1.3;
            color: #1a1916;
        }

        /* Header */
        .header {
            display: block;
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1.5px solid #1a1916;
        }
        .header img {
            width: 48px;
            height: auto;
        }
        .header-org {
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 4px 0 2px;
        }
        .header-sub {
            font-size: 8pt;
            color: #4a4843;
        }
        .header h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 4px 0 2px;
        }
        .header h3 {
            font-size: 9pt;
            font-weight: bold;
            margin: 0;
            color: #e05c1a;
        }
        .kategori-chip {
            display: inline-block;
            background: #fff3ec;
            border: 0.5px solid #f0b088;
            border-radius: 4px;
            padding: 2px 10px;
            font-size: 7pt;
            color: #c94d13;
            margin-top: 4px;
        }

        /* Meta strip */
        .meta-strip {
            margin-bottom: 8px;
            font-size: 7.5pt;
            color: #4a4843;
        }

        table.data-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 0;
        }
        table.data-table th {
            background-color: #2c2c2a;
            color: #ffffff;
            border: 0.5px solid #2c2c2a;
            padding: 4px 3px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            font-size: 6.5pt;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.2;
        }
        table.data-table td {
            border: 0.5px solid #d1cec6;
            padding: 3px 4px;
            vertical-align: top;
            font-size: 7pt;
            text-align: left;
            color: #1a1916;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.3;
        }
        table.data-table tbody tr:nth-child(even) td {
            background-color: #f7f6f3;
        }
        td.num { text-align: right; }

        /* Footer */
        .footer-note {
            margin-top: 10px;
            font-size: 7pt;
            color: #e05c1a;
            padding-top: 5px;
            border-top: 0.5px solid #e4e2dc;
        }
        .footer-right { color: #8a8880; }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('assets/img/garuda.png') }}" alt="Garuda Pancasila">
        <div class="header-org">BADAN PUSAT STATISTIK</div>
        <div class="header-sub">Laporan Data PDRB</div>
        <h2>DATA PDRB {{ strtoupper($triwulan->triwulan) }}</h2>
        <h3>TAHUN {{ $tahun }}</h3>
        @if(isset($showCategoryHeader) && $showCategoryHeader)
            <div><span class="kategori-chip">{{ $kategori->kode }} — {{ $kategori->nama }}</span></div>
        @endif
    </div>

    <!-- Meta strip -->
    <div class="meta-strip">
        <strong>Triwulan:</strong> {{ $triwulan->triwulan }} &nbsp;|&nbsp;
        <strong>Tahun:</strong> {{ $tahun }} &nbsp;|&nbsp;
        <strong>Kategori:</strong> {{ $kategori->kode }} — {{ $kategori->nama }}
    </div>

    <!-- Data table -->
    <table class="data-table">
        <thead>
            <tr>
                @foreach($visibleColumns as $column)
                    @php
                        /*
                         * Lebar kolom dalam persen.
                         * Total harus <= 100%. Dengan table-layout:fixed, mPDF
                         * akan menghormati nilai ini dan melakukan word-wrap
                         * secara horizontal (bukan vertikal).
                         *
                         * Kelompok kategori (4 kolom): 9% x 4 = 36%
                         * Nama komoditas             : 12%
                         * Indikator                  : 7%
                         * Satuan-satuan (4 kolom)    : 5% x 4 = 20%
                         * Angka-angka (8 kolom)      : 4% x 8 = 32% (tapi hanya 8 kolom angka max)
                         * Sisa dibagi rata oleh browser/mPDF untuk kolom default.
                         */
                        $width = match($column->column_key) {
                            'kategori'              => '10%',
                            'sub_kategori_1'        => '9%',
                            'sub_kategori_2'        => '9%',
                            'sub_kategori_3'        => '8%',
                            'nama_komoditas'        => '12%',
                            'indikator'             => '7%',
                            'satuan_produksi'       => '5%',
                            'satuan_harga'          => '5%',
                            'satuan_luas'           => '5%',
                            'satuan_biaya_perawatan'=> '5%',
                            'produksi'              => '6%',
                            'harga'                 => '6%',
                            'rasio_output_ikutan'   => '5%',
                            'rasio_wip_cbr'         => '5%',
                            'rasio_biaya_antara'    => '5%',
                            'ihp'                   => '5%',
                            'luas_tanam'            => '5%',
                            'biaya_perawatan'       => '5%',
                            default                 => '6%',
                        };
                    @endphp
                    <th style="width:{{ $width }}">{{ $column->column_label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
            /* Resolve leaf nodes */
            $leafData = array_filter($data, function($item) use ($data) {
                foreach ($data as $check) {
                    if ($check['commodity']->parent_id == $item['commodity']->id) return false;
                }
                return true;
            });

            if (empty($leafData)) {
                $maxLevel = max(array_map(fn($i) => $i['commodity']->level, $data));
                $leafData = array_filter($data, fn($i) => $i['commodity']->level == $maxLevel);
            }

            /* Group by hierarchy */
            $grouped = [];
            foreach ($leafData as $item) {
                $h  = $item['hierarchy'];
                $c  = $item['commodity'];
                $l1 = $h['level1'] ? $h['level1']->id : 0;
                $l2 = $h['level2'] ? $h['level2']->id : 0;
                $l3 = $h['level3'] ? $h['level3']->id : 0;
                $l4 = $h['level4'] ? $h['level4']->id : 0;

                if (!isset($grouped[$l1])) $grouped[$l1] = ['level1' => $h['level1'], 'level2s' => []];
                if (!isset($grouped[$l1]['level2s'][$l2])) $grouped[$l1]['level2s'][$l2] = ['level2' => $h['level2'], 'level3s' => []];
                if (!isset($grouped[$l1]['level2s'][$l2]['level3s'][$l3])) $grouped[$l1]['level2s'][$l2]['level3s'][$l3] = ['level3' => $h['level3'], 'level4s' => [], 'direct_items' => []];

                if ($c->level == 5 && $h['level4']) {
                    if (!isset($grouped[$l1]['level2s'][$l2]['level3s'][$l3]['level4s'][$l4]))
                        $grouped[$l1]['level2s'][$l2]['level3s'][$l3]['level4s'][$l4] = ['level4' => $h['level4'], 'items' => []];
                    $grouped[$l1]['level2s'][$l2]['level3s'][$l3]['level4s'][$l4]['items'][] = $item;
                } else {
                    $grouped[$l1]['level2s'][$l2]['level3s'][$l3]['direct_items'][] = $item;
                }
            }

            /* Kolom kategori yang visible (perlu rowspan) */
            $catKeys     = ['kategori', 'sub_kategori_1', 'sub_kategori_2', 'sub_kategori_3'];
            $visCatCols  = $visibleColumns->whereIn('column_key', $catKeys)->pluck('column_key')->toArray();

            /* Kolom angka (right-align) */
            $numKeys = ['produksi','harga','rasio_output_ikutan','rasio_wip_cbr','rasio_biaya_antara','ihp','luas_tanam','biaya_perawatan'];

            /* Closure render nilai sel */
            $cellValue = function (string $key, array $item, $l1 = null, $l2 = null, $l3 = null, $l4 = null): string {
                $c   = $item['commodity'];
                $pp  = $item['price_production'] ?? null;
                $rs  = $item['rasio']  ?? null;
                $wc  = $item['wip_cbr'] ?? null;
                $ihp = $item['ihp']    ?? null;

                $fmt = fn($v, $d = 2) => ($v === null || $v === '')
                    ? '—'
                    : rtrim(rtrim(number_format((float)$v, $d, ',', '.'), '0'), ',');

                return match($key) {
                    'kategori'               => $l1 ? $l1->kode . '<br>' . $l1->nama : '—',
                    'sub_kategori_1'         => $l2 ? $l2->kode . '<br>' . $l2->nama : '—',
                    'sub_kategori_2'         => $l3 ? $l3->kode . '<br>' . $l3->nama : '—',
                    'sub_kategori_3'         => $l4 ? $l4->kode . '<br>' . $l4->nama : '—',
                    'nama_komoditas'         => $c->nama ?? '—',
                    'indikator'              => $item['indikator']->indikator               ?? '—',
                    'satuan_produksi'        => $item['satuan_produksi']->satuan_produksi   ?? '—',
                    'satuan_harga'           => $item['satuan_harga']->satuan_harga         ?? '—',
                    'satuan_luas'            => $item['satuan_luas_tanam']->satuan_luas_tanam ?? '—',
                    'satuan_biaya_perawatan' => $item['satuan_biaya_perawatan']->satuan_biaya_perawatan ?? '—',
                    'produksi'               => $pp  ? $fmt($pp->produksi  ?? null) : '—',
                    'harga'                  => $pp  ? $fmt($pp->harga     ?? null) : '—',
                    'rasio_output_ikutan'    => $rs  ? $fmt($rs->rasio_output_ikutan  ?? null) : '—',
                    'rasio_wip_cbr'          => $rs  ? $fmt($rs->rasio_wip_cbr        ?? null) : '—',
                    'rasio_biaya_antara'     => $rs  ? $fmt($rs->rasio_biaya_antara   ?? null) : '—',
                    'ihp'                    => $ihp ? $fmt($ihp->ihp ?? null) : '—',
                    'luas_tanam'             => $wc  ? $fmt($wc->luas_tanam_akhir_tahun ?? null) : '—',
                    'biaya_perawatan'        => $wc  ? $fmt($wc->biaya_perawatan       ?? null) : '—',
                    default                  => '—',
                };
            };
            @endphp

            @foreach($grouped as $l1Data)
                @php $level1 = $l1Data['level1']; @endphp
                @foreach($l1Data['level2s'] as $l2Data)
                    @php $level2 = $l2Data['level2']; @endphp
                    @foreach($l2Data['level3s'] as $l3Data)
                        @php $level3 = $l3Data['level3']; @endphp

                        <!-- Direct items (no level 4) -->
                        @if(!empty($l3Data['direct_items']))
                            @foreach($l3Data['direct_items'] as $idx => $item)
                                @php
                                    $isFirst = ($idx === 0);
                                    $rowspan = count($l3Data['direct_items']);
                                @endphp
                                <tr>
                                    @foreach($visibleColumns as $col)
                                        @php
                                            $isCat = in_array($col->column_key, $visCatCols);
                                            $isNum = in_array($col->column_key, $numKeys);
                                            $val   = $cellValue($col->column_key, $item, $level1, $level2, $level3, null);
                                        @endphp
                                        @if($isCat && $isFirst)
                                            <td rowspan="{{ $rowspan }}" style="vertical-align:top">{!! $val !!}</td>
                                        @elseif(!$isCat)
                                            <td @if($isNum) class="num" @endif>{!! $val !!}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif

                        <!-- Items under level 4 -->
                        @foreach($l3Data['level4s'] ?? [] as $l4Data)
                            @php $level4 = $l4Data['level4']; $items = $l4Data['items']; @endphp
                            @foreach($items as $idx => $item)
                                @php $isFirst = ($idx === 0); $rowspan = count($items); @endphp
                                <tr>
                                    @foreach($visibleColumns as $col)
                                        @php
                                            $isCat = in_array($col->column_key, $visCatCols);
                                            $isNum = in_array($col->column_key, $numKeys);
                                            $val   = $cellValue($col->column_key, $item, $level1, $level2, $level3, $level4);
                                        @endphp
                                        @if($isCat && $isFirst)
                                            <td rowspan="{{ $rowspan }}" style="vertical-align:top">{!! $val !!}</td>
                                        @elseif(!$isCat)
                                            <td @if($isNum) class="num" @endif>{!! $val !!}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach

                    @endforeach
                @endforeach
            @endforeach

            @if(count($leafData) === 0)
                <tr>
                    <td colspan="{{ count($visibleColumns) }}" style="text-align:center;color:#8a8880;padding:16px;">
                        Tidak ada data untuk kategori, tahun, dan triwulan yang dipilih.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer-note">
        Laporan di-generate otomatis oleh SI-PRABU &nbsp;·&nbsp;
        <span class="footer-right">Tanggal: {{ now()->format('d-m-Y H:i') }}</span>
    </div>

</body>
</html>