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
                <h4 class="fw-bold mb-2">Tren Harga</h4>
                <h2 class="fw-bold text-primary mb-0">{{ $item_name }}</h2>
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
                    @if (count($prices) > 0)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="price-info">
                                    @php
                                        // Get the last price record
                                        $lastPriceRecord = end($prices);
                                        // Reset array pointer to ensure we can use reset() properly
                                        reset($prices);
                                        // Get the first price record
                                        $firstPriceRecord = reset($prices);
                                        
                                        // Calculate price difference between first and last record's current prices
                                        $firstPrice = $firstPriceRecord['initial_price'];
                                        $lastPrice = $lastPriceRecord['current_price'];
                                        $priceDiff = $lastPrice - $firstPrice;
                                        
                                        // Calculate percentage change safely to avoid division by zero
                                        $percentChange = $firstPrice > 0 ? ($priceDiff / $firstPrice) * 100 : 0;
                                    @endphp
                                    <p class="text-muted mb-1">Harga Awal</p>
                                    <h3 class="fw-bold">Rp {{ number_format($firstPrice, 0, ',', '.') }}</h3><br>
                                    
                                    <p class="text-muted mb-1">Harga Terakhir</p>
                                    <h3 class="fw-bold">Rp {{ number_format($lastPrice, 0, ',', '.') }}</h3><br>
                                    
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
                                    Data harga untuk barang ini belum tersedia.
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
    @if (count($prices) > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hide loader when chart is ready
                setTimeout(() => {
                    document.getElementById('chartLoader').style.display = 'none';
                }, 800);

                // Prepare data for candlestick chart
                const dates = {!! json_encode(
                    collect($prices)->pluck('updated_at')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M Y'))->toArray(),
                ) !!};

                const initialPrices = {!! json_encode(collect($prices)->pluck('initial_price')) !!};
                const currentPrices = {!! json_encode(collect($prices)->pluck('current_price')) !!};

                // Create candlestick data (OHLC format)
                const candlestickData = [];

                for (let i = 0; i < dates.length; i++) {
                    // For candlestick, we need open, high, low, close values
                    // Since we only have initial and current prices, we'll use them as open and close
                    // For high and low, we'll calculate based on the available data
                    const open = initialPrices[i];
                    const close = currentPrices[i];

                    // For demonstration purposes, we'll create high and low values
                    // In a real scenario, you would have actual high and low values from your database
                    const high = Math.max(open, close) * (1 + Math.random() * 0.05); // 0-5% higher
                    const low = Math.min(open, close) * (1 - Math.random() * 0.05); // 0-5% lower

                    candlestickData.push({
                        x: new Date(dates[i]),
                        y: [open, high, low, close]
                    });
                }

                // Chart options
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
                    tooltip: {
                        enabled: true,
                        theme: 'light',
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function(y) {
                                return 'Rp ' + y.toLocaleString('id-ID');
                            }
                        }
                    },
                    xaxis: {
                        type: 'category',
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
                                return 'Rp ' + val.toLocaleString('id-ID');
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
                    responsive: [{
                        breakpoint: 768,
                        options: {
                            chart: {
                                height: 300
                            }
                        }
                    }]
                };

                // Initialize chart
                const chart = new ApexCharts(document.getElementById('priceChart'), options);
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
                        pdf.text('Tren Harga: {{ $item_name }}', 10, 15);

                        // Add date
                        pdf.setFontSize(10);
                        pdf.setFont(undefined, 'normal');
                        pdf.text('Diekspor pada: ' + new Date().toLocaleString('id-ID'), 10, 22);

                        // Add chart
                        pdf.addImage(imgData, "PNG", 10, 30, pdfWidth, pdfHeight);

                        // Save PDF
                        pdf.save("chart-harga-{{ \Illuminate\Support\Str::slug($item_name) }}.pdf");

                        // Reset button
                        exportBtn.innerHTML = originalText;
                        exportBtn.disabled = false;
                    });
                });
            });
        </script>
    @endif
@endsection