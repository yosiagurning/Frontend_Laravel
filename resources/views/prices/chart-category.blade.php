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

    .btn-back, .btn-export {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-export {
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
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
                                $sortedHistories = collect($histories)->sortBy('created_at');
                                $groupedByDate = $sortedHistories->groupBy(function ($item) {
                                    return \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d');
                                });
                                $firstDate = $groupedByDate->keys()->first();
                                $lastDate = $groupedByDate->keys()->last();
                                $firstTotalPrice = $groupedByDate[$firstDate]->sum('initial_price');
                                $lastTotalPrice = $groupedByDate[$lastDate]->sum('current_price');
                                $priceDiff = $lastTotalPrice - $firstTotalPrice;
                                $percentChange = $firstTotalPrice > 0 ? ($priceDiff / $firstTotalPrice) * 100 : 0;
                            @endphp
                            <p class="text-muted mb-1">Total Harga Awal ({{ \Carbon\Carbon::parse($firstDate)->format('d M Y') }})</p>
                            <h3 class="fw-bold">Rp {{ number_format($firstTotalPrice, 0, ',', '.') }}</h3><br>
                            <p class="text-muted mb-1">Total Harga Terakhir ({{ \Carbon\Carbon::parse($lastDate)->format('d M Y') }})</p>
                            <h3 class="fw-bold">Rp {{ number_format($lastTotalPrice, 0, ',', '.') }}</h3><br>
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
                    <div class="text-end mt-3" id="trendDirection"></div>
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
// Konfigurasi chart dengan warna tegas dan logika yang diperbaiki
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        document.getElementById('chartLoader').style.display = 'none';
    }, 800);

    const rawData = {!! json_encode($histories) !!};

    // Perbaikan logika pengelompokan dan pengurutan data
    const grouped = {};
    rawData.forEach(item => {
        const date = new Date(item.created_at).toISOString().split('T')[0];
        if (!grouped[date]) grouped[date] = 0;
        grouped[date] += parseFloat(item.current_price);
    });

    // Pastikan data diurutkan berdasarkan tanggal (chronological)
    const sortedDates = Object.keys(grouped).sort((a, b) => new Date(a) - new Date(b));
    
    // Buat array data yang sudah diurutkan
    const lineChartData = sortedDates.map(date => ({
        x: date,
        y: grouped[date]
    }));

    // Tentukan tren berdasarkan dua titik data terakhir
    let trendArrow = 'fa-minus';
    let trendColor = '#666666'; // Abu-abu untuk stabil
    let trendText = 'Stabil';
    let trendBgColor = '#F3F4F6';
    let trendFillColor = '#E5E7EB';

    if (lineChartData.length >= 2) {
        const prev = lineChartData[lineChartData.length - 2].y;
        const last = lineChartData[lineChartData.length - 1].y;

        if (last > prev) {
            trendArrow = 'fa-arrow-up';
            trendColor = '#FF0000'; // Merah tegas untuk naik
            trendText = 'NAIK';
            trendBgColor = '#FEF2F2';
            trendFillColor = '#FECACA';
        } else if (last < prev) {
            trendArrow = 'fa-arrow-down';
            trendColor = '#00AA00'; // Hijau tegas untuk turun
            trendText = 'TURUN';
            trendBgColor = '#F0FDF4';
            trendFillColor = '#BBF7D0';
        }
    }

    // Persiapkan data untuk markers dengan warna yang sesuai
    const markerColors = [];
    for (let i = 0; i < lineChartData.length; i++) {
        if (i === 0) {
            markerColors.push('#1F2937'); // Titik pertama abu-abu gelap
        } else {
            const current = lineChartData[i].y;
            const previous = lineChartData[i-1].y;
            if (current > previous) {
                markerColors.push('#FF0000'); // Naik - merah
            } else if (current < previous) {
                markerColors.push('#00AA00'); // Turun - hijau
            } else {
                markerColors.push('#666666'); // Stabil - abu-abu
            }
        }
    }

    document.getElementById('trendDirection').innerHTML = `
        <div class="badge fs-6 py-2 px-3" style="background-color: ${trendColor}; color: white;">
            <i class="fas ${trendArrow} me-2"></i> TREN: ${trendText}
        </div>
    `;

    const options = {
        series: [{
            name: 'Total Harga',
            data: lineChartData
        }],
        chart: {
            type: 'line',
            height: 550,
            fontFamily: 'Inter, system-ui, sans-serif',
            background: '#FFFFFF',
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
        colors: [trendColor], // Warna garis sesuai tren
        stroke: {
            curve: 'straight', // Garis lurus untuk kejelasan
            width: 6, // Garis sangat tebal
            lineCap: 'round'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: [trendFillColor],
                inverseColors: false,
                opacityFrom: 0.8,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        markers: {
            size: 10, // Marker besar
            strokeWidth: 4,
            strokeColors: '#FFFFFF',
            hover: {
                size: 14,
                sizeOffset: 4
            },
            discrete: lineChartData.map((point, index) => ({
                seriesIndex: 0,
                dataPointIndex: index,
                fillColor: markerColors[index],
                strokeColor: '#FFFFFF',
                size: index === lineChartData.length - 1 ? 14 : 10, // Marker terakhir lebih besar
                shape: 'circle'
            }))
        },
        grid: {
            show: true,
            borderColor: '#000000', // Grid hitam
            strokeDashArray: 0, // Garis solid
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
                colors: ['#FFFFFF', '#F8F9FA'], // Alternating row colors
                opacity: 0.8
            },
            column: {
                colors: ['#FFFFFF', '#F1F3F4'], // Alternating column colors
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
                    return new Date(val).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                },
                rotate: -45,
                rotateAlways: false,
                hideOverlappingLabels: true
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
                text: 'TOTAL HARGA (RUPIAH)',
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
                
                // Highlight jika ini adalah data terakhir
                const isLastPoint = dataPointIndex === series[seriesIndex].length - 1;
                const highlightStyle = isLastPoint ? 'border: 4px solid #FF6600; box-shadow: 0 0 15px rgba(255, 102, 0, 0.5);' : '';
                const lastPointLabel = isLastPoint ? '<div style="background: #FF6600; color: white; text-align: center; padding: 4px; margin: -16px -16px 12px -16px; border-radius: 8px 8px 0 0; font-weight: 900;">DATA TERAKHIR</div>' : '';
                
                return `<div style="background: white; ${highlightStyle} border-radius: 12px; padding: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.3); min-width: 250px;">
                            ${lastPointLabel}
                            <div style="background: ${trendBg}; color: ${trendColor}; font-weight: 900; margin: ${isLastPoint ? '0' : '-16px'} -16px 12px -16px; padding: 12px 16px; border-radius: ${isLastPoint ? '0' : '8px 8px 0 0'}; font-size: 16px; text-align: center;">
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
            text: 'GRAFIK TREN HARGA KATEGORI',
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
        annotations: {
            points: [
                // Highlight titik terakhir dengan anotasi khusus
                {
                    x: lineChartData[lineChartData.length - 1]?.x,
                    y: lineChartData[lineChartData.length - 1]?.y,
                    marker: {
                        size: 16,
                        fillColor: markerColors[lineChartData.length - 1],
                        strokeColor: '#FF6600',
                        strokeWidth: 4,
                        radius: 16
                    },
                    label: {
                        text: 'HARGA TERAKHIR',
                        borderColor: '#FF6600',
                        borderWidth: 3,
                        borderRadius: 8,
                        textAnchor: 'middle',
                        position: 'top',
                        offsetY: -15,
                        style: {
                            background: '#FF6600',
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
            ],
            // Tambahkan garis untuk menandai titik terakhir
            xaxis: [{
                x: lineChartData[lineChartData.length - 1]?.x,
                strokeDashArray: 0,
                borderColor: '#FF6600',
                fillColor: '#FFF3E0',
                opacity: 0.2,
                offsetX: 0,
                offsetY: 0,
                label: {
                    borderColor: '#FF6600',
                    borderWidth: 2,
                    borderRadius: 5,
                    text: 'DATA TERAKHIR',
                    textAnchor: 'middle',
                    position: 'bottom',
                    orientation: 'horizontal',
                    offsetY: 10,
                    style: {
                        background: '#FF6600',
                        color: '#FFFFFF',
                        fontSize: '12px',
                        fontWeight: 700,
                        padding: {
                            left: 10,
                            right: 10,
                            top: 5,
                            bottom: 5
                        }
                    }
                }
            }]
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

    const chart = new ApexCharts(document.querySelector("#priceChart"), options);
    chart.render();

    document.getElementById("exportChartPDF").addEventListener("click", function () {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF("landscape");
        const exportBtn = this;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Exporting...';
        exportBtn.disabled = true;

        html2canvas(document.getElementById("priceChart")).then(canvas => {
            const imgData = canvas.toDataURL("image/png");
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth() - 20;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.setFontSize(16);
            pdf.setFont(undefined, 'bold');
            pdf.text('Tren Harga Kategori: {{ $category_name ?? 'Semua Kategori' }}', 10, 15);
            pdf.setFontSize(10);
            pdf.setFont(undefined, 'normal');
            pdf.text('Diekspor pada: ' + new Date().toLocaleString('id-ID'), 10, 22);
            pdf.addImage(imgData, "PNG", 10, 30, pdfWidth, pdfHeight);

            pdf.save("chart-harga-kategori-{{ \Illuminate\Support\Str::slug($category_name ?? 'semua-kategori') }}.pdf");

            exportBtn.innerHTML = '<i class="fas fa-file-pdf me-2"></i> Export Chart ke PDF';
            exportBtn.disabled = false;
        });
    });
});
</script>
@endif
@endsection
