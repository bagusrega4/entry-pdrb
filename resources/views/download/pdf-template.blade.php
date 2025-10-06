<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data PDRB {{ $triwulan->triwulan }} - {{ $tahun }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 180px;
            height: auto;
            margin-bottom: 5px;
        }

        .header h3 {
            margin: 6px 0;
            font-size: 12pt;
        }

        .tahun {
            color: #000000;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9pt;
        }

        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            font-size: 9pt;
        }

        table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 9pt;
            text-align: left;
        }

        .footer-note {
            margin-top: 15px;
            font-size: 9pt;
            color: #c41e3a;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('assets/img/garuda.png') }}" alt="Garuda">
        <h3>DATA PDRB <span class="tahun">{{ strtoupper($triwulan->triwulan) }}</span></h3>
        <h3>Tahun <span class="tahun">{{ $tahun }}</span></h3>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($visibleColumns as $column)
                    @php
                        $width = 'auto';
                        switch($column->column_key) {
                            case 'kategori':
                            case 'sub_kategori_1':
                            case 'sub_kategori_2':
                            case 'sub_kategori_3':
                                $width = '8%';
                                break;
                            case 'nama_komoditas':
                                $width = '12%';
                                break;
                            case 'indikator':
                                $width = '8%';
                                break;
                            case 'satuan_produksi':
                            case 'satuan_harga':
                            case 'satuan_luas':
                            case 'satuan_biaya_perawatan':
                                $width = '6%';
                                break;
                            default:
                                $width = '7%';
                        }
                    @endphp
                    <th style="width: {{ $width }};">{{ $column->column_label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
            // Filter hanya komoditas yang merupakan leaf (tidak punya children)
            $leafData = array_filter($data, function($item) use ($data) {
                $commodity = $item['commodity'];
                
                // Cek apakah commodity ini punya children di array data
                $hasChildren = false;
                foreach($data as $checkItem) {
                    if ($checkItem['commodity']->parent_id == $commodity->id) {
                        $hasChildren = true;
                        break;
                    }
                }
                
                // Return true jika tidak punya children (adalah leaf node)
                return !$hasChildren;
            });

            // PENTING: Jika tidak ada leaf data, tampilkan semua data level terakhir
            if (empty($leafData)) {
                // Cari level tertinggi dalam data
                $maxLevel = 0;
                foreach($data as $item) {
                    if ($item['commodity']->level > $maxLevel) {
                        $maxLevel = $item['commodity']->level;
                    }
                }
                
                // Filter data dengan level tertinggi
                $leafData = array_filter($data, function($item) use ($maxLevel) {
                    return $item['commodity']->level == $maxLevel;
                });
            }

            $grouped = [];
            foreach($leafData as $item) {
                $h = $item['hierarchy'];
                $commodity = $item['commodity'];
                
                $level1Id = isset($h['level1']) && $h['level1'] ? $h['level1']->id : 0;
                $level2Id = isset($h['level2']) && $h['level2'] ? $h['level2']->id : 0;
                $level3Id = isset($h['level3']) && $h['level3'] ? $h['level3']->id : 0;
                $level4Id = isset($h['level4']) && $h['level4'] ? $h['level4']->id : 0;

                if (!isset($grouped[$level1Id])) {
                    $grouped[$level1Id] = [
                        'level1' => $h['level1'] ?? null,
                        'level2s' => []
                    ];
                }

                if (!isset($grouped[$level1Id]['level2s'][$level2Id])) {
                    $grouped[$level1Id]['level2s'][$level2Id] = [
                        'level2' => $h['level2'] ?? null,
                        'level3s' => []
                    ];
                }

                if (!isset($grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id])) {
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id] = [
                        'level3' => $h['level3'] ?? null,
                        'level4s' => [],
                        'direct_items' => []
                    ];
                }

                // Handle berbagai level commodity
                if ($commodity->level <= 3) {
                    // Komoditas level 1, 2, atau 3 langsung tanpa sub kategori lebih lanjut
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['direct_items'][] = $item;
                } elseif ($commodity->level == 4) {
                    // Komoditas level 4
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['direct_items'][] = $item;
                } elseif ($commodity->level == 5 && isset($h['level4']) && $h['level4']) {
                    // Komoditas level 5 dengan parent level 4
                    if (!isset($grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['level4s'][$level4Id])) {
                        $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['level4s'][$level4Id] = [
                            'level4' => $h['level4'],
                            'items' => []
                        ];
                    }
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['level4s'][$level4Id]['items'][] = $item;
                } else {
                    // Fallback: masukkan sebagai direct_items
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['direct_items'][] = $item;
                }
            }

            // Helper function untuk format angka tanpa trailing zeros
            if (!function_exists('formatNumber')) {
                function formatNumber($value, $decimals = 2) {
                    if ($value === null || $value === '') {
                        return '-';
                    }
                    $formatted = number_format($value, $decimals, ',', '.');
                    $formatted = rtrim($formatted, '0');
                    $formatted = rtrim($formatted, ',');
                    return $formatted;
                }
            }

            if (!function_exists('renderColumnValue')) {
                function renderColumnValue($columnKey, $item, $level1 = null, $level2 = null, $level3 = null, $level4 = null) {
                    $commodity = $item['commodity'];
                    $priceProduction = $item['price_production'];
                    $rasio = $item['rasio'] ?? null;
                    $wipCbr = $item['wip_cbr'] ?? null;
                    $ihp = $item['ihp'] ?? null;
                    
                    switch($columnKey) {
                        case 'kategori':
                            return $level1 ? $level1->kode . '<br>' . $level1->nama : '-';
                        case 'sub_kategori_1':
                            return $level2 ? $level2->kode . '<br>' . $level2->nama : '-';
                        case 'sub_kategori_2':
                            return $level3 ? $level3->kode . '<br>' . $level3->nama : '-';
                        case 'sub_kategori_3':
                            return $level4 ? $level4->kode . '<br>' . $level4->nama : '-';
                        case 'nama_komoditas':
                            return $commodity->nama;
                        case 'indikator':
                            return isset($item['indikator']) && $item['indikator'] ? $item['indikator']->indikator : '-';
                        case 'satuan_produksi':
                            return isset($item['satuan_produksi']) && $item['satuan_produksi'] ? $item['satuan_produksi']->satuan_produksi : '-';
                        case 'satuan_harga':
                            return isset($item['satuan_harga']) && $item['satuan_harga'] ? $item['satuan_harga']->satuan_harga : '-';
                        case 'satuan_luas':
                            return isset($item['satuan_luas_tanam']) && $item['satuan_luas_tanam'] ? $item['satuan_luas_tanam']->satuan_luas_tanam : '-';
                        case 'satuan_biaya_perawatan':
                            return isset($item['satuan_biaya_perawatan']) && $item['satuan_biaya_perawatan'] ? $item['satuan_biaya_perawatan']->satuan_biaya_perawatan : '-';
                        case 'produksi':
                            return $priceProduction && isset($priceProduction->produksi)
                                ? formatNumber($priceProduction->produksi, 2)
                                : '-';
                        case 'harga':
                            return $priceProduction && isset($priceProduction->harga)
                                ? formatNumber($priceProduction->harga, 2)
                                : '-';
                        case 'rasio_output_ikutan':
                            return $rasio && isset($rasio->rasio_output_ikutan)
                                ? formatNumber($rasio->rasio_output_ikutan, 2)
                                : '-';
                        case 'rasio_wip_cbr':
                            return $rasio && isset($rasio->rasio_wip_cbr)
                                ? formatNumber($rasio->rasio_wip_cbr, 2)
                                : '-';
                        case 'rasio_biaya_antara':
                            return $rasio && isset($rasio->rasio_biaya_antara)
                                ? formatNumber($rasio->rasio_biaya_antara, 2)
                                : '-';
                        case 'ihp':
                            return $ihp && isset($ihp->ihp)
                                ? formatNumber($ihp->ihp, 2)
                                : '-';
                        case 'luas_tanam':
                            return $wipCbr && isset($wipCbr->luas_tanam_akhir_tahun)
                                ? formatNumber($wipCbr->luas_tanam_akhir_tahun, 2)
                                : '-';
                        case 'biaya_perawatan':
                            return $wipCbr && isset($wipCbr->biaya_perawatan)
                                ? formatNumber($wipCbr->biaya_perawatan, 2)
                                : '-';
                        default:
                            return '-';
                    }
                }
            }

            // Get column keys untuk kategori (perlu rowspan)
            $categoryColumns = ['kategori', 'sub_kategori_1', 'sub_kategori_2', 'sub_kategori_3'];
            $visibleCategoryColumns = $visibleColumns->whereIn('column_key', $categoryColumns)->pluck('column_key')->toArray();
            @endphp

            @foreach($grouped as $level1Data)
                @php $level1 = $level1Data['level1']; @endphp

                @foreach($level1Data['level2s'] as $level2Data)
                    @php $level2 = $level2Data['level2']; @endphp

                    @foreach($level2Data['level3s'] as $level3Data)
                        @php $level3 = $level3Data['level3']; @endphp

                        {{-- Handle items yang langsung ada di level3 (tanpa level4) --}}
                        @if(isset($level3Data['direct_items']) && count($level3Data['direct_items']) > 0)
                            @foreach($level3Data['direct_items'] as $itemIndex => $item)
                                @php
                                $isFirstInGroup = ($itemIndex === 0);
                                $rowspanCount = count($level3Data['direct_items']);
                                @endphp

                                <tr>
                                    @foreach($visibleColumns as $column)
                                        @php
                                        $columnKey = $column->column_key;
                                        $isCategoryColumn = in_array($columnKey, $visibleCategoryColumns);
                                        $value = renderColumnValue($columnKey, $item, $level1, $level2, $level3, null);
                                        @endphp

                                        @if($isCategoryColumn && $isFirstInGroup)
                                            <td rowspan="{{ $rowspanCount }}">{!! $value !!}</td>
                                        @elseif(!$isCategoryColumn)
                                            <td>{!! $value !!}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif

                        {{-- Handle items yang ada di level4 --}}
                        @foreach($level3Data['level4s'] ?? [] as $level4Data)
                            @php 
                                $level4 = $level4Data['level4'];
                                $items = $level4Data['items'];
                            @endphp

                            @foreach($items as $itemIndex => $item)
                                @php
                                $isFirstInGroup = ($itemIndex === 0);
                                $rowspanCount = count($items);
                                @endphp

                                <tr>
                                    @foreach($visibleColumns as $column)
                                        @php
                                        $columnKey = $column->column_key;
                                        $isCategoryColumn = in_array($columnKey, $visibleCategoryColumns);
                                        $value = renderColumnValue($columnKey, $item, $level1, $level2, $level3, $level4);
                                        @endphp

                                        @if($isCategoryColumn && $isFirstInGroup)
                                            <td rowspan="{{ $rowspanCount }}">{!! $value !!}</td>
                                        @elseif(!$isCategoryColumn)
                                            <td>{!! $value !!}</td>
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
                    <td colspan="{{ count($visibleColumns) }}" style="text-align: center;">
                        Tidak ada data untuk kategori, tahun, dan triwulan yang dipilih
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-note">
        Data Di generate tanggal : {{ now()->format('d-m-Y') }}
    </div>
</body>
</html>