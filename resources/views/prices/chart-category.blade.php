@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .trend-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .trend-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .chart-container {
            min-height: 400px;
            position: relative;
        }

        .chart-loader {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 10;
        }

        .btn-back {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-export {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            background-color: #fff;
            color: #dc3545;
            border: 1px solid #dc3545;
            transition: all 0.2s;
        }

        .btn-export:hover {
            background-color: #dc3545;
            color: white;
        }

        .price-info {
            border-left: 4px solid #0d6efd;
            padding-left: 15px;
        }

        .no-data {
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h4 class="fw-bold mb-2">Tren Harga per Kategori</h4>
                <h2 class="fw-bold text-primary mb-0">{{ $category_name ?? 'Semua Kategori' }}</h2>
            </div>
            <div class="col-md-4 text-md-end d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
                <a href="{{ url()->previous() }}" class="btn btn-back btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="trend-card p-4 mb-4">
                    @if (!empty($histories) && count($histories) > 0)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="price-info">
                                    @php
                                        // Sort the histories by created_at
                                        $sortedHistories = collect($histories)->sortBy('created_at');
                                        
                                        // Group by date to get total prices per day
                                        $groupedByDate = $sortedHistories->groupBy(function ($item) {
                                            return \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d');
                                        });
                                        
                                        // Get the first and last dates
                                        $firstDate = $groupedByDate->keys()->first();
                                        $lastDate = $groupedByDate->keys()->last();
                                        
                                        // Get data for first and last dates
                                        $firstDateData = $groupedByDate[$firstDate];
                                        $lastDateData = $groupedByDate[$lastDate];
                                        
                                        // Calculate total prices for first and last dates
                                        $firstTotalPrice = $firstDateData->sum('initial_price');
                                        $lastTotalPrice = $lastDateData->sum('current_price');
                                        
                                        // Calculate price difference and percentage change
                                        $priceDiff = $lastTotalPrice - $firstTotalPrice;
                                        $percentChange = $firstTotalPrice > 0 ? ($priceDiff / $firstTotalPrice) * 100 : 0;
                                    @endphp
                                    <p class="text-muted mb-1">Total Harga Awal ({{ \Carbon\Carbon::parse($firstDate)->format('d M Y') }})</p>
                                    <h3 class="fw-bold">Rp {{ number_format($firstTotalPrice, 0, ',', '.') }}</h3><br>
                                    <p class="text-muted mb-1">Total Harga Terakhir ({{ \Carbon\Carbon::parse($lastDate)->format('d M Y') }})</p>
                                    <h3 class="fw-bold">Rp {{ number_format($lastTotalPrice, 0, ',', '.') }}</h3><br>
                                    <span class="badge {{ $priceDiff >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        <i class="fas fa-{{ $priceDiff >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                        {{ number_format(abs($percentChange), 2) }}%
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <button id="exportChartPDF" class="btn btn-export">
                                    <i class="fas fa-file-pdf me-2"></i> Export Chart ke PDF
                                </button>
                            </div>
                        </div>

                        <div class="chart-container">
                            <div id="chartLoader" class="chart-loader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="priceChart"></div>
                        </div>
                    @else
                        <div class="no-data">
                            <div class="text-center">
                                <i class="fas fa-chart-line text-muted mb-3" style="font-size: 3rem;"></i>
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Data harga untuk kategori ini belum tersedia.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    @if (!empty($histories) && count($histories) > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hide loader when chart is ready
                setTimeout(() => {
                    document.getElementById('chartLoader').style.display = 'none';
                }, 800);

                const rawData = {!! json_encode($histories) !!};

                // Fungsi untuk memformat angka ke format Rupiah
                const formatRupiah = (number) => {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
                };

                // Fungsi untuk mengolah data menjadi format candlestick
                const processCandlestickData = (data) => {
                    // Group data by date to consolidate multiple entries on the same day
                    const groupedByDate = {};
                    
                    data.forEach(item => {
                        const dateObj = new Date(item.created_at);
                        const dateStr = dateObj.toISOString().split('T')[0]; // YYYY-MM-DD format
                        
                        if (!groupedByDate[dateStr]) {
                            groupedByDate[dateStr] = {
                                date: dateObj,
                                initialPrices: [],
                                currentPrices: []
                            };
                        }
                        
                        groupedByDate[dateStr].initialPrices.push(parseFloat(item.initial_price));
                        groupedByDate[dateStr].currentPrices.push(parseFloat(item.current_price));
                    });
                    
                    // Convert grouped data to candlestick format
                    const formatted = Object.keys(groupedByDate).map(dateStr => {
                        const group = groupedByDate[dateStr];
                        
                        // Calculate average initial and current prices for this date
                        const avgInitial = group.initialPrices.reduce((sum, price) => sum + price, 0) / group.initialPrices.length;
                        const avgCurrent = group.currentPrices.reduce((sum, price) => sum + price, 0) / group.currentPrices.length;
                        
                        // For candlestick, we need open, high, low, close values
                        const open = avgInitial;
                        const close = avgCurrent;
                        
                        // Find actual max and min from the data
                        const high = Math.max(open, close, ...group.initialPrices, ...group.currentPrices);
                        const low = Math.min(open, close, ...group.initialPrices, ...group.currentPrices);
                        
                        return {
                            x: group.date,
                            y: [open, high, low, close]
                        };
                    });
                    
                    // Sort by date
                    return formatted.sort((a, b) => a.x - b.x);
                };

                const candlestickData = processCandlestickData(rawData);

                const options = {
                    series: [{
                        name: 'Harga',
                        data: candlestickData
                    }],
                    chart: {
                        type: 'candlestick',
                        height: 400,
                        fontFamily: 'inherit',
                        toolbar: {
                            show: true,
                            tools: {
                                download: false,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: true,
                                reset: true
                            }
                        },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800
                        }
                    },
                    title: {
                        text: 'Perubahan Harga Berdasarkan Tanggal',
                        align: 'left',
                        style: {
                            fontSize: '16px',
                            fontWeight: 600
                        }
                    },
                    xaxis: {
                        type: 'datetime',
                        labels: {
                            formatter: function(val) {
                                return new Date(val).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            }
                        },
                        axisBorder: {
                            show: true,
                            color: '#e0e0e0'
                        },
                        crosshairs: {
                            show: true,
                            width: 1,
                            position: 'back',
                            opacity: 0.9
                        }
                    },
                    yaxis: {
                        tooltip: {
                            enabled: true
                        },
                        labels: {
                            formatter: function(val) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    },
                    plotOptions: {
                        candlestick: {
                            colors: {
                                upward: '#26a69a',
                                downward: '#ef5350'
                            },
                            wick: {
                                useFillColor: true
                            }
                        }
                    },
                    tooltip: {
                        custom: function({
                            seriesIndex,
                            dataPointIndex,
                            w
                        }) {
                            const o = w.globals.seriesCandleO[seriesIndex][dataPointIndex];
                            const h = w.globals.seriesCandleH[seriesIndex][dataPointIndex];
                            const l = w.globals.seriesCandleL[seriesIndex][dataPointIndex];
                            const c = w.globals.seriesCandleC[seriesIndex][dataPointIndex];
                            const x = w.globals.seriesX[seriesIndex][dataPointIndex];
                            const date = new Date(x).toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            return `
                        <div class="apexcharts-tooltip-candlestick p-3">
                            <div class="mb-2"><strong>${date}</strong></div>
                            <div>Harga Awal: <strong>${formatRupiah(o)}</strong></div>
                            <div>Tertinggi: <strong>${formatRupiah(h)}</strong></div>
                            <div>Terendah: <strong>${formatRupiah(l)}</strong></div>
                            <div>Harga Akhir: <strong>${formatRupiah(c)}</strong></div>
                        </div>
                    `;
                        }
                    },
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 300
                            }
                        }
                    }]
                };

                const chart = new ApexCharts(document.querySelector("#priceChart"), options);
                chart.render();

                // Export chart to PDF
                document.getElementById("exportChartPDF").addEventListener("click", function() {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const pdf = new jsPDF("landscape");

                    // Show loading state
                    const exportBtn = this;
                    const originalText = exportBtn.innerHTML;
                    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Exporting...';
                    exportBtn.disabled = true;

                    // Capture chart
                    html2canvas(document.getElementById("priceChart")).then(canvas => {
                        const imgData = canvas.toDataURL("image/png");
                        const imgProps = pdf.getImageProperties(imgData);
                        const pdfWidth = pdf.internal.pageSize.getWidth() - 20;
                        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                        // Add title
                        pdf.setFontSize(16);
                        pdf.setFont(undefined, 'bold');
                        pdf.text('Tren Harga Kategori: {{ $category_name ?? 'Semua Kategori' }}', 10,
                            15);

                        // Add date
                        pdf.setFontSize(10);
                        pdf.setFont(undefined, 'normal');
                        pdf.text('Diekspor pada: ' + new Date().toLocaleString('id-ID'), 10, 22);

                        // Add chart
                        pdf.addImage(imgData, "PNG", 10, 30, pdfWidth, pdfHeight);

                        // Save PDF
                        pdf.save(
                            "chart-harga-kategori-{{ \Illuminate\Support\Str::slug($category_name ?? 'semua-kategori') }}.pdf"
                            );

                        // Reset button
                        exportBtn.innerHTML = originalText;
                        exportBtn.disabled = false;
                    });
                });
            });
        </script>
    @endif
@endsection