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
                                    
                                    <span class="badge {{ $priceDiff >= 0 ? 'bg-danger' : 'bg-success' }}">
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
// Konfigurasi chart dengan garis yang lebih jelas dan grid yang lengkap
// Konfigurasi chart dengan warna yang sangat tegas dan kontras tinggi
document.addEventListener('DOMContentLoaded', function() {
    // Hide loader when chart is ready
    setTimeout(() => {
        document.getElementById('chartLoader').style.display = 'none';
    }, 800);

    // Prepare data for line chart with color coding
    const rawData = {!! json_encode(
        collect($prices)->map(fn($p) => [
            'x' => \Carbon\Carbon::parse($p['created_at'])->format('Y-m-d'),
            'y' => $p['current_price'],
        ])
    ) !!};

    // Function to determine color based on price trend - VERY BOLD COLORS
    function getColorBasedOnTrend(currentPrice, previousPrice) {
        if (previousPrice === null || previousPrice === undefined) {
            return '#1F2937'; // Very dark gray for first point
        }
        return currentPrice > previousPrice ? '#FF0000' : '#00AA00'; // Pure red for up, Pure green for down
    }

    // Process data to add colors and create segments
    const processedData = [];
    
    for (let i = 0; i < rawData.length; i++) {
        const currentPoint = rawData[i];
        const previousPoint = i > 0 ? rawData[i - 1] : null;
        
        processedData.push({
            x: currentPoint.x,
            y: currentPoint.y,
            fillColor: getColorBasedOnTrend(currentPoint.y, previousPoint?.y),
            strokeColor: getColorBasedOnTrend(currentPoint.y, previousPoint?.y)
        });
    }

    const options = {
        series: [{
            name: 'Harga',
            data: rawData
        }],
        chart: {
            type: 'line',
            height: 550,
            zoom: { 
                enabled: true,
                type: 'x',
                autoScaleYaxis: true
            },
            toolbar: { 
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                }
            },
            background: '#FFFFFF', // Pure white background
            fontFamily: 'Inter, system-ui, sans-serif',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            },
            dropShadow: {
                enabled: true,
                color: '#000000',
                top: 2,
                left: 2,
                blur: 4,
                opacity: 0.3
            }
        },
        colors: ['#0066FF'], // Bright blue default
        stroke: {
            curve: 'straight', // Changed to straight for sharper lines
            width: 6, // Very thick line
            lineCap: 'round'
        },
        fill: {
            type: 'solid',
            opacity: 0.1
        },
        markers: {
            size: 10, // Very large markers
            strokeWidth: 4,
            strokeColors: '#FFFFFF',
            hover: {
                size: 14,
                sizeOffset: 4
            },
            discrete: processedData.map((point, index) => ({
                seriesIndex: 0,
                dataPointIndex: index,
                fillColor: point.fillColor,
                strokeColor: '#FFFFFF',
                size: 10,
                shape: 'circle'
            }))
        },
        grid: {
            show: true,
            borderColor: '#000000', // Black grid lines
            strokeDashArray: 0, // Solid lines
            position: 'back',
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            row: {
                colors: ['#FFFFFF', '#F8F9FA'], // Strong alternating colors
                opacity: 0.8
            },
            column: {
                colors: ['#FFFFFF', '#F1F3F4'], // Strong alternating colors
                opacity: 0.6
            },
            padding: {
                top: 15,
                right: 15,
                bottom: 15,
                left: 15
            }
        },
        xaxis: {
            type: 'category',
            title: {
                text: 'PERIODE WAKTU',
                style: { 
                    fontWeight: 900,
                    fontSize: '16px',
                    color: '#000000'
                }
            },
            labels: {
                style: {
                    colors: '#000000',
                    fontSize: '14px',
                    fontWeight: 800
                },
                formatter: function (val) {
                    const date = new Date(val);
                    return date.toLocaleDateString('id-ID', { 
                        day: '2-digit', 
                        month: 'short', 
                        year: 'numeric' 
                    });
                },
                rotate: -45,
                rotateAlways: false,
                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false
            },
            axisBorder: {
                show: true,
                color: '#000000',
                height: 3,
                width: '100%'
            },
            axisTicks: {
                show: true,
                borderType: 'solid',
                color: '#000000',
                height: 10,
                width: 2
            },
            crosshairs: {
                show: true,
                width: 2,
                position: 'back',
                opacity: 1,
                stroke: {
                    color: '#FF6600',
                    width: 2,
                    dashArray: 0
                }
            }
        },
        yaxis: {
            title: {
                text: 'HARGA (RUPIAH)',
                style: { 
                    fontWeight: 900,
                    fontSize: '16px',
                    color: '#000000'
                }
            },
            labels: {
                style: {
                    colors: '#000000',
                    fontSize: '14px',
                    fontWeight: 800
                },
                formatter: function (val) {
                    return 'Rp ' + val.toLocaleString('id-ID');
                }
            },
            axisBorder: {
                show: true,
                color: '#000000',
                width: 3
            },
            axisTicks: {
                show: true,
                borderType: 'solid',
                color: '#000000',
                width: 10
            },
            crosshairs: {
                show: true,
                position: 'back',
                stroke: {
                    color: '#FF6600',
                    width: 2,
                    dashArray: 0
                }
            }
        },
        tooltip: {
            enabled: true,
            shared: true,
            followCursor: true,
            intersect: false,
            theme: 'light',
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, system-ui, sans-serif'
            },
            custom: function({series, seriesIndex, dataPointIndex, w}) {
                const currentVal = series[seriesIndex][dataPointIndex];
                const previousVal = dataPointIndex > 0 ? series[seriesIndex][dataPointIndex - 1] : null;
                const date = w.globals.categoryLabels[dataPointIndex];
                
                let trendIcon = '';
                let trendColor = '';
                let trendBg = '';
                let trendText = '';
                
                if (previousVal !== null) {
                    if (currentVal > previousVal) {
                        trendIcon = '🔴';
                        trendColor = '#FFFFFF';
                        trendBg = '#FF0000';
                        const increase = currentVal - previousVal;
                        const percentage = ((increase / previousVal) * 100).toFixed(2);
                        trendText = `NAIK +${percentage}%`;
                    } else if (currentVal < previousVal) {
                        trendIcon = '🟢';
                        trendColor = '#FFFFFF';
                        trendBg = '#00AA00';
                        const decrease = previousVal - currentVal;
                        const percentage = ((decrease / previousVal) * 100).toFixed(2);
                        trendText = `TURUN -${percentage}%`;
                    } else {
                        trendIcon = '⚪';
                        trendColor = '#FFFFFF';
                        trendBg = '#666666';
                        trendText = 'TIDAK BERUBAH';
                    }
                } else {
                    trendIcon = '⚫';
                    trendColor = '#FFFFFF';
                    trendBg = '#333333';
                    trendText = 'DATA PERTAMA';
                }
                
                return `<div style="background: white; border: 4px solid ${trendBg}; border-radius: 12px; padding: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.3); min-width: 250px;">
                            <div style="background: ${trendBg}; color: ${trendColor}; font-weight: 900; margin: -16px -16px 12px -16px; padding: 12px 16px; border-radius: 8px 8px 0 0; font-size: 16px; text-align: center;">
                                ${trendIcon} ${trendText}
                            </div>
                            <div style="font-weight: 900; font-size: 20px; color: #000000; margin-bottom: 8px; text-align: center;">
                                Rp ${currentVal.toLocaleString('id-ID')}
                            </div>
                            <div style="color: #333333; font-size: 13px; font-weight: 700; text-align: center; border-top: 2px solid #E5E7EB; padding-top: 8px; margin-top: 8px;">
                                ${new Date(date).toLocaleDateString('id-ID', { 
                                    weekday: 'long',
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric' 
                                })}
                            </div>
                        </div>`;
            }
        },
        title: {
            text: 'GRAFIK PERUBAHAN HARGA',
            align: 'center',
            margin: 20,
            offsetX: 0,
            offsetY: 0,
            floating: false,
            style: {
                fontSize: '24px',
                fontWeight: 900,
                color: '#000000'
            }
        },
        subtitle: {
            text: '🔴 MERAH: HARGA NAIK | 🟢 HIJAU: HARGA TURUN',
            align: 'center',
            margin: 20,
            offsetX: 0,
            offsetY: 40,
            floating: false,
            style: {
                fontSize: '16px',
                fontWeight: 800,
                color: '#333333'
            }
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'center',
            floating: false,
            fontSize: '16px',
            fontFamily: 'Inter, system-ui, sans-serif',
            fontWeight: 800,
            offsetX: 0,
            offsetY: 0,
            labels: {
                colors: '#000000'
            },
            markers: {
                width: 16,
                height: 16,
                strokeWidth: 0,
                strokeColor: '#000000',
                radius: 16,
                offsetX: 0,
                offsetY: 0
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0
            }
        },
        annotations: {
            points: [
                {
                    x: rawData[Math.floor(rawData.length * 0.15)]?.x,
                    y: Math.max(...rawData.map(d => d.y)) * 0.95,
                    marker: {
                        size: 14,
                        fillColor: '#FF0000',
                        strokeColor: '#FFFFFF',
                        strokeWidth: 4
                    },
                    label: {
                        text: '🔴 HARGA NAIK',
                        borderColor: '#FF0000',
                        borderWidth: 3,
                        borderRadius: 8,
                        textAnchor: 'start',
                        position: 'right',
                        offsetX: 20,
                        style: {
                            background: '#FF0000',
                            color: '#FFFFFF',
                            fontSize: '14px',
                            fontWeight: 900,
                            padding: {
                                left: 12,
                                right: 12,
                                top: 8,
                                bottom: 8
                            }
                        }
                    }
                },
                {
                    x: rawData[Math.floor(rawData.length * 0.45)]?.x,
                    y: Math.max(...rawData.map(d => d.y)) * 0.95,
                    marker: {
                        size: 14,
                        fillColor: '#00AA00',
                        strokeColor: '#FFFFFF',
                        strokeWidth: 4
                    },
                    label: {
                        text: '🟢 HARGA TURUN',
                        borderColor: '#00AA00',
                        borderWidth: 3,
                        borderRadius: 8,
                        textAnchor: 'start',
                        position: 'right',
                        offsetX: 20,
                        style: {
                            background: '#00AA00',
                            color: '#FFFFFF',
                            fontSize: '14px',
                            fontWeight: 900,
                            padding: {
                                left: 12,
                                right: 12,
                                top: 8,
                                bottom: 8
                            }
                        }
                    }
                }
            ]
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 450
                },
                title: {
                    style: {
                        fontSize: '20px'
                    }
                },
                subtitle: {
                    style: {
                        fontSize: '14px'
                    }
                },
                stroke: {
                    width: 4
                },
                markers: {
                    size: 8
                },
                xaxis: {
                    labels: {
                        rotate: -90,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                annotations: {
                    points: []
                },
                grid: {
                    padding: {
                        top: 10,
                        right: 10,
                        bottom: 10,
                        left: 10
                    }
                }
            }
        }]
    };

    // Initialize chart
    const chart = new ApexCharts(document.getElementById('priceChart'), options);
    chart.render();

    // Export chart to PDF (existing code remains the same)
    document.getElementById("exportChartPDF").addEventListener("click", function() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF("landscape");

        const exportBtn = this;
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Exporting...';
        exportBtn.disabled = true;

        html2canvas(document.getElementById("priceChart")).then(canvas => {
            const imgData = canvas.toDataURL("image/png");
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth() - 20;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.setFontSize(16);
            pdf.setFont(undefined, 'bold');
            pdf.text('Tren Harga: {{ $item_name }}', 10, 15);

            pdf.setFontSize(10);
            pdf.setFont(undefined, 'normal');
            pdf.text('Diekspor pada: ' + new Date().toLocaleString('id-ID'), 10, 22);

            pdf.addImage(imgData, "PNG", 10, 30, pdfWidth, pdfHeight);
            pdf.save("chart-harga-{{ \Illuminate\Support\Str::slug($item_name) }}.pdf");

            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        });
    });
});
        </script>
    @endif
@endsection