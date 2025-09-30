<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data PDRB {{ $triwulan->triwulan }} - {{ $tahun }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 10mm;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header img {
            width: 180px;
            height: auto;
            margin-bottom: 1px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 13pt;
            font-weight: bold;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 10pt;
        }

        .tahun {
            color: #c41e3a;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8pt;
        }

        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        table td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .footer-note {
            margin-top: 12px;
            font-size: 8pt;
            color: #c41e3a;
        }

        .page-break {
            page-break-after: always;
        }

        /* Prevent table from breaking across pages */
        table {
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        tfoot {
            display: table-footer-group;
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
                <th style="width: 9%;">Kategori</th>
                <th style="width: 11%;">Sub Kategori 1</th>
                <th style="width: 11%;">Sub Kategori 2</th>
                <th style="width: 11%;">Sub Kategori 3</th>
                <th style="width: 16%;">Nama Komoditas</th>
                <th style="width: 10%;">Indikator</th>
                <th style="width: 10%;">Satuan Produksi</th>
                <th style="width: 11%;">Produksi</th>
                <th style="width: 11%;">Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
            $globalRowCount = 0;

            // Filter hanya data yang merupakan leaf nodes (komoditas terakhir)
            // Level 4 = komoditas langsung dari Sub Kategori 2
            // Level 5 = komoditas dari Sub Kategori 3
            $leafData = array_filter($data, function($item) use ($data) {
                $commodity = $item['commodity'];
                
                // Cek apakah commodity ini punya children
                $hasChildren = false;
                foreach($data as $checkItem) {
                    if ($checkItem['commodity']->parent_id == $commodity->id) {
                        $hasChildren = true;
                        break;
                    }
                }
                
                // Hanya ambil yang tidak punya children (leaf nodes)
                return !$hasChildren;
            });

            // Group berdasarkan hierarchy path
            $grouped = [];
            foreach($leafData as $item) {
                $h = $item['hierarchy'];
                $commodity = $item['commodity'];
                
                $level1Id = $h['level1']->id ?? 0;
                $level2Id = $h['level2']->id ?? 0;
                $level3Id = $h['level3']->id ?? 0;
                $level4Id = $h['level4']->id ?? 0;

                if (!isset($grouped[$level1Id])) {
                    $grouped[$level1Id] = [
                        'level1' => $h['level1'],
                        'level2s' => []
                    ];
                }

                if (!isset($grouped[$level1Id]['level2s'][$level2Id])) {
                    $grouped[$level1Id]['level2s'][$level2Id] = [
                        'level2' => $h['level2'],
                        'level3s' => []
                    ];
                }

                if (!isset($grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id])) {
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id] = [
                        'level3' => $h['level3'],
                        'level4s' => []
                    ];
                }

                // Cek apakah ini komoditas level 4 (langsung dari level 3) atau level 5 (dari level 4)
                if ($commodity->level == 4) {
                    // Level 4 = komoditas langsung, tidak ada Sub Kategori 3
                    if (!isset($grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['direct_items'])) {
                        $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['direct_items'] = [];
                    }
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['direct_items'][] = $item;
                } elseif ($commodity->level == 5 && $h['level4']) {
                    // Level 5 = ada Sub Kategori 3 (level4)
                    if (!isset($grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['level4s'][$level4Id])) {
                        $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['level4s'][$level4Id] = [
                            'level4' => $h['level4'],
                            'items' => []
                        ];
                    }
                    $grouped[$level1Id]['level2s'][$level2Id]['level3s'][$level3Id]['level4s'][$level4Id]['items'][] = $item;
                }
            }
            @endphp

            @foreach($grouped as $level1Data)
                @php $level1 = $level1Data['level1']; @endphp

                @foreach($level1Data['level2s'] as $level2Data)
                    @php $level2 = $level2Data['level2']; @endphp

                    @foreach($level2Data['level3s'] as $level3Data)
                        @php $level3 = $level3Data['level3']; @endphp

                        {{-- Handle items yang langsung ada di level3 (tanpa level4) --}}
                        @if(isset($level3Data['direct_items']))
                            @foreach($level3Data['direct_items'] as $itemIndex => $item)
                                @php
                                $commodity = $item['commodity'];
                                $priceProduction = $item['price_production'];
                                $globalRowCount++;

                                $posInPage = (($globalRowCount - 1) % 25) + 1;
                                $isFirstInGroup = ($itemIndex === 0);
                                $showCategoryColumns = $isFirstInGroup;

                                $remainingInGroup = count($level3Data['direct_items']) - $itemIndex;
                                $rowspanCount = $remainingInGroup;
                                @endphp

                                <tr>
                                    @if($showCategoryColumns)
                                        <td rowspan="{{ $rowspanCount }}">
                                            {{ $level1->kode }}<br>
                                            {{ $level1->nama }}
                                        </td>
                                        <td rowspan="{{ $rowspanCount }}">
                                            {{ $level2->kode }}<br>
                                            {{ $level2->nama }}
                                        </td>
                                        <td rowspan="{{ $rowspanCount }}">
                                            {{ $level3->kode }}<br>
                                            {{ $level3->nama }}
                                        </td>
                                        <td rowspan="{{ $rowspanCount }}" class="text-center">-</td>
                                    @endif

                                    <td>{{ $commodity->nama }}</td>
                                    <td class="text-center">
                                        {{ $item['indikator']->indikator ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['satuan_produksi']->satuan_produksi ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if($priceProduction && $priceProduction->produksi)
                                            {{ number_format($priceProduction->produksi, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($priceProduction && $priceProduction->harga)
                                            {{ number_format($priceProduction->harga, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
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
                                $commodity = $item['commodity'];
                                $priceProduction = $item['price_production'];
                                $globalRowCount++;

                                $posInPage = (($globalRowCount - 1) % 25) + 1;
                                $isFirstInGroup = ($itemIndex === 0);
                                $showCategoryColumns = $isFirstInGroup;

                                $remainingInGroup = count($items) - $itemIndex;
                                $rowspanCount = $remainingInGroup;
                                @endphp

                                <tr>
                                    @if($showCategoryColumns)
                                        <td rowspan="{{ $rowspanCount }}">
                                            {{ $level1->kode }}<br>
                                            {{ $level1->nama }}
                                        </td>
                                        <td rowspan="{{ $rowspanCount }}">
                                            {{ $level2->kode }}<br>
                                            {{ $level2->nama }}
                                        </td>
                                        <td rowspan="{{ $rowspanCount }}">
                                            {{ $level3->kode }}<br>
                                            {{ $level3->nama }}
                                        </td>
                                        <td rowspan="{{ $rowspanCount }}">
                                            {{ $level4->kode }}<br>
                                            {{ $level4->nama }}
                                        </td>
                                    @endif

                                    <td>{{ $commodity->nama }}</td>
                                    <td class="text-center">
                                        {{ $item['indikator']->indikator ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['satuan_produksi']->satuan_produksi ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if($priceProduction && $priceProduction->produksi)
                                            {{ number_format($priceProduction->produksi, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($priceProduction && $priceProduction->harga)
                                            {{ number_format($priceProduction->harga, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach

            @if(count($leafData) === 0)
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data untuk kategori, tahun, dan triwulan yang dipilih</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-note">
        Data Di generate tanggal : {{ now()->format('d-m-Y') }}
    </div>
</body>

</html>