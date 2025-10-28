# API Dokumentasi - Statistik untuk Chart.js

## 📊 Endpoint Statistik

### 1. Dashboard Statistik (Summary Cards)

**Endpoint:** `GET /api/statistik/dashboard`  
**Auth:** Bearer Token (Sanctum)  
**Method:** GET

**Deskripsi:** Mendapatkan ringkasan statistik untuk menampilkan di cards dashboard (bukan untuk chart)

**Response:**

```json
{
    "status": true,
    "message": "Dashboard statistik berhasil diambil.",
    "data": {
        "cards": {
            "hari_ini": {
                "total_transaksi": 5,
                "total_pendapatan": 250000
            },
            "minggu_ini": {
                "total_transaksi": 35,
                "total_pendapatan": 1750000
            },
            "bulan_ini": {
                "total_transaksi": 150,
                "total_pendapatan": 7500000
            },
            "tahun_ini": {
                "total_transaksi": 1800,
                "total_pendapatan": 90000000
            }
        },
        "inventory": {
            "total_produk": 120,
            "total_kategori": 15,
            "produk_stok_menipis": 8
        }
    }
}
```

---

### 2. Statistik Comparison (Bar Chart)

**Endpoint:** `GET /api/statistik/comparison`  
**Auth:** Bearer Token (Sanctum)  
**Method:** GET

**Deskripsi:** Data untuk membandingkan performa hari ini, minggu ini, bulan ini, dan tahun ini dalam 1 bar chart

**Response:**

```json
{
    "status": true,
    "message": "Statistik comparison berhasil diambil.",
    "chart_data": {
        "labels": ["Hari Ini", "Minggu Ini", "Bulan Ini", "Tahun Ini"],
        "datasets": [
            {
                "label": "Jumlah Transaksi",
                "data": [5, 35, 150, 1800],
                "backgroundColor": [
                    "rgba(255, 99, 132, 0.5)",
                    "rgba(54, 162, 235, 0.5)",
                    "rgba(255, 206, 86, 0.5)",
                    "rgba(75, 192, 192, 0.5)"
                ],
                "borderColor": [
                    "rgba(255, 99, 132, 1)",
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 206, 86, 1)",
                    "rgba(75, 192, 192, 1)"
                ],
                "borderWidth": 2
            },
            {
                "label": "Total Pendapatan (Rp)",
                "data": [250000, 1750000, 7500000, 90000000],
                "backgroundColor": [
                    "rgba(153, 102, 255, 0.5)",
                    "rgba(255, 159, 64, 0.5)",
                    "rgba(199, 199, 199, 0.5)",
                    "rgba(83, 102, 255, 0.5)"
                ],
                "borderColor": [
                    "rgba(153, 102, 255, 1)",
                    "rgba(255, 159, 64, 1)",
                    "rgba(199, 199, 199, 1)",
                    "rgba(83, 102, 255, 1)"
                ],
                "borderWidth": 2,
                "yAxisID": "y1"
            }
        ]
    }
}
```

---

### 3. Statistik Tahunan (Per Bulan)

**Endpoint:** `GET /api/statistik/tahunan`  
**Auth:** Bearer Token (Sanctum)  
**Method:** GET

**Query Parameters:**

-   `tahun` (optional): Tahun yang ingin ditampilkan, default: tahun berjalan

**Contoh Request:**

```
GET /api/statistik/tahunan?tahun=2025
```

**Response:**

```json
{
    "status": true,
    "message": "Statistik penjualan tahunan berhasil diambil.",
    "periode": {
        "type": "yearly",
        "tahun": 2025
    },
    "chart_data": {
        "labels": [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Agt",
            "Sep",
            "Okt",
            "Nov",
            "Des"
        ],
        "datasets": [
            {
                "label": "Jumlah Transaksi",
                "data": [45, 52, 60, 48, 70, 65, 80, 75, 90, 85, 0, 0],
                "backgroundColor": "rgba(54, 162, 235, 0.5)",
                "borderColor": "rgba(54, 162, 235, 1)",
                "borderWidth": 2,
                "tension": 0.4
            },
            {
                "label": "Total Pendapatan (Rp)",
                "data": [
                    2250000, 2600000, 3000000, 2400000, 3500000, 3250000,
                    4000000, 3750000, 4500000, 4250000, 0, 0
                ],
                "backgroundColor": "rgba(75, 192, 192, 0.5)",
                "borderColor": "rgba(75, 192, 192, 1)",
                "borderWidth": 2,
                "tension": 0.4,
                "yAxisID": "y1"
            }
        ]
    },
    "summary": {
        "total_transaksi": 670,
        "total_pendapatan": 33500000,
        "rata_rata_per_bulan": 3350000,
        "bulan_terbaik": {
            "bulan": "Sep",
            "pendapatan": 4500000
        }
    }
}
```

---

### 4. Statistik Bulanan (Per Hari)

**Endpoint:** `GET /api/statistik/bulanan`  
**Auth:** Bearer Token (Sanctum)  
**Method:** GET

**Query Parameters:**

-   `bulan` (optional): Bulan (1-12), default: bulan berjalan
-   `tahun` (optional): Tahun, default: tahun berjalan

**Contoh Request:**

```
GET /api/statistik/bulanan?bulan=10&tahun=2025
```

**Response:**

```json
{
    "status": true,
    "message": "Statistik penjualan bulanan berhasil diambil.",
    "periode": {
        "type": "monthly",
        "bulan": 10,
        "tahun": 2025,
        "nama_bulan": "Oktober"
    },
    "chart_data": {
        "labels": [
            1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
            20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31
        ],
        "datasets": [
            {
                "label": "Jumlah Transaksi",
                "data": [
                    3, 5, 2, 4, 6, 3, 2, 5, 4, 6, 3, 5, 7, 4, 6, 3, 5, 4, 6, 3,
                    5, 4, 6, 3, 5, 4, 6, 3, 5, 0, 0
                ],
                "backgroundColor": "rgba(255, 99, 132, 0.5)",
                "borderColor": "rgba(255, 99, 132, 1)",
                "borderWidth": 2,
                "tension": 0.4
            },
            {
                "label": "Total Pendapatan (Rp)",
                "data": [
                    150000, 250000, 100000, 200000, 300000, 150000, 100000,
                    250000, 200000, 300000, 150000, 250000, 350000, 200000,
                    300000, 150000, 250000, 200000, 300000, 150000, 250000,
                    200000, 300000, 150000, 250000, 200000, 300000, 150000,
                    250000, 0, 0
                ],
                "backgroundColor": "rgba(153, 102, 255, 0.5)",
                "borderColor": "rgba(153, 102, 255, 1)",
                "borderWidth": 2,
                "tension": 0.4,
                "yAxisID": "y1"
            }
        ]
    },
    "summary": {
        "total_transaksi": 130,
        "total_pendapatan": 6500000,
        "rata_rata_per_hari": 224137.93,
        "hari_terbaik": {
            "tanggal": 13,
            "pendapatan": 350000
        }
    }
}
```

---

### 5. Statistik Mingguan (7 Hari Terakhir)

**Endpoint:** `GET /api/statistik/mingguan`  
**Auth:** Bearer Token (Sanctum)  
**Method:** GET

**Query Parameters:**

-   `tanggal_akhir` (optional): Tanggal akhir (format: Y-m-d), default: hari ini

**Contoh Request:**

```
GET /api/statistik/mingguan
GET /api/statistik/mingguan?tanggal_akhir=2025-10-29
```

**Response:**

```json
{
    "status": true,
    "message": "Statistik penjualan mingguan berhasil diambil.",
    "periode": {
        "type": "weekly",
        "tanggal_awal": "2025-10-23",
        "tanggal_akhir": "2025-10-29"
    },
    "chart_data": {
        "labels": [
            "Rab, 23",
            "Kam, 24",
            "Jum, 25",
            "Sab, 26",
            "Min, 27",
            "Sen, 28",
            "Sel, 29"
        ],
        "datasets": [
            {
                "label": "Jumlah Transaksi",
                "data": [5, 6, 8, 12, 15, 7, 10],
                "backgroundColor": "rgba(255, 206, 86, 0.5)",
                "borderColor": "rgba(255, 206, 86, 1)",
                "borderWidth": 2,
                "tension": 0.4
            },
            {
                "label": "Total Pendapatan (Rp)",
                "data": [
                    250000, 300000, 400000, 600000, 750000, 350000, 500000
                ],
                "backgroundColor": "rgba(75, 192, 192, 0.5)",
                "borderColor": "rgba(75, 192, 192, 1)",
                "borderWidth": 2,
                "tension": 0.4,
                "yAxisID": "y1"
            }
        ]
    },
    "summary": {
        "total_transaksi": 63,
        "total_pendapatan": 3150000,
        "rata_rata_per_hari": 450000,
        "hari_terbaik": {
            "hari": "Min, 27",
            "pendapatan": 750000
        }
    }
}
```

---

## 🎨 Implementasi dengan Chart.js

### Layout Dashboard yang Direkomendasikan

Untuk dashboard yang optimal, gunakan kombinasi endpoint berikut:

1. **`/api/statistik/dashboard`** - Untuk menampilkan cards/angka statistik
2. **`/api/statistik/comparison`** - Untuk 1 bar chart perbandingan
3. **`/api/statistik/tahunan`** - Untuk line chart trend tahunan (12 bulan)
4. **`/api/statistik/bulanan`** - Untuk line chart trend bulanan (per hari)
5. **`/api/statistik/mingguan`** - Untuk line chart trend mingguan (7 hari)

---

### Contoh 1: Dashboard Cards (Tanpa Chart)

```javascript
import { useEffect, useState } from "react";

function DashboardCards() {
    const [stats, setStats] = useState(null);

    useEffect(() => {
        fetchDashboardStats();
    }, []);

    const fetchDashboardStats = async () => {
        try {
            const response = await fetch(
                "http://your-api.com/api/statistik/dashboard",
                {
                    headers: {
                        Authorization: "Bearer " + token,
                    },
                }
            );

            const result = await response.json();
            if (result.status) {
                setStats(result.data);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    };

    if (!stats) return <div>Loading...</div>;

    return (
        <div className="grid grid-cols-4 gap-4">
            <div className="card">
                <h3>Hari Ini</h3>
                <p className="text-2xl">
                    {stats.cards.hari_ini.total_transaksi}
                </p>
                <p className="text-sm">Transaksi</p>
                <p className="text-xl">
                    Rp{" "}
                    {stats.cards.hari_ini.total_pendapatan.toLocaleString(
                        "id-ID"
                    )}
                </p>
            </div>

            <div className="card">
                <h3>Minggu Ini</h3>
                <p className="text-2xl">
                    {stats.cards.minggu_ini.total_transaksi}
                </p>
                <p className="text-sm">Transaksi</p>
                <p className="text-xl">
                    Rp{" "}
                    {stats.cards.minggu_ini.total_pendapatan.toLocaleString(
                        "id-ID"
                    )}
                </p>
            </div>

            <div className="card">
                <h3>Bulan Ini</h3>
                <p className="text-2xl">
                    {stats.cards.bulan_ini.total_transaksi}
                </p>
                <p className="text-sm">Transaksi</p>
                <p className="text-xl">
                    Rp{" "}
                    {stats.cards.bulan_ini.total_pendapatan.toLocaleString(
                        "id-ID"
                    )}
                </p>
            </div>

            <div className="card">
                <h3>Tahun Ini</h3>
                <p className="text-2xl">
                    {stats.cards.tahun_ini.total_transaksi}
                </p>
                <p className="text-sm">Transaksi</p>
                <p className="text-xl">
                    Rp{" "}
                    {stats.cards.tahun_ini.total_pendapatan.toLocaleString(
                        "id-ID"
                    )}
                </p>
            </div>
        </div>
    );
}
```

---

### Contoh 2: Comparison Bar Chart

```javascript
import { useEffect, useState } from "react";
import { Bar } from "react-chartjs-2";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

function ComparisonChart() {
    const [chartData, setChartData] = useState(null);

    useEffect(() => {
        fetchComparisonStats();
    }, []);

    const fetchComparisonStats = async () => {
        try {
            const response = await fetch(
                "http://your-api.com/api/statistik/comparison",
                {
                    headers: {
                        Authorization: "Bearer " + token,
                    },
                }
            );

            const result = await response.json();
            if (result.status) {
                setChartData(result.chart_data);
            }
        } catch (error) {
            console.error("Error:", error);
        }
    };

    const options = {
        responsive: true,
        interaction: {
            mode: "index",
            intersect: false,
        },
        plugins: {
            legend: {
                position: "top",
            },
            title: {
                display: true,
                text: "Perbandingan Performa Penjualan",
            },
        },
        scales: {
            y: {
                type: "linear",
                display: true,
                position: "left",
                title: {
                    display: true,
                    text: "Jumlah Transaksi",
                },
            },
            y1: {
                type: "linear",
                display: true,
                position: "right",
                title: {
                    display: true,
                    text: "Pendapatan (Rp)",
                },
                grid: {
                    drawOnChartArea: false,
                },
            },
        },
    };

    return (
        <div className="chart-container">
            {chartData && <Bar options={options} data={chartData} />}
        </div>
    );
}
```

---

### Contoh 3: Trend Tahunan (Line Chart)

```javascript
import { useEffect, useState } from "react";
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
} from "chart.js";
import { Line } from "react-chartjs-2";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

function StatistikTahunan() {
    const [chartData, setChartData] = useState(null);
    const [summary, setSummary] = useState(null);

    useEffect(() => {
        fetchStatistik();
    }, []);

    const fetchStatistik = async () => {
        try {
            const response = await fetch(
                "http://your-api.com/api/statistik/tahunan",
                {
                    headers: {
                        Authorization: "Bearer " + token,
                        "Content-Type": "application/json",
                    },
                }
            );

            const result = await response.json();

            if (result.status) {
                setChartData(result.chart_data);
                setSummary(result.summary);
            }
        } catch (error) {
            console.error("Error fetching statistik:", error);
        }
    };

    const options = {
        responsive: true,
        interaction: {
            mode: "index",
            intersect: false,
        },
        plugins: {
            legend: {
                position: "top",
            },
            title: {
                display: true,
                text: "Statistik Penjualan Tahunan",
            },
        },
        scales: {
            y: {
                type: "linear",
                display: true,
                position: "left",
                title: {
                    display: true,
                    text: "Jumlah Transaksi",
                },
            },
            y1: {
                type: "linear",
                display: true,
                position: "right",
                title: {
                    display: true,
                    text: "Pendapatan (Rp)",
                },
                grid: {
                    drawOnChartArea: false,
                },
            },
        },
    };

    return (
        <div>
            {chartData && <Line options={options} data={chartData} />}

            {summary && (
                <div className="summary-cards">
                    <div className="card">
                        <h3>Total Transaksi</h3>
                        <p>{summary.total_transaksi}</p>
                    </div>
                    <div className="card">
                        <h3>Total Pendapatan</h3>
                        <p>
                            Rp{" "}
                            {summary.total_pendapatan.toLocaleString("id-ID")}
                        </p>
                    </div>
                    <div className="card">
                        <h3>Bulan Terbaik</h3>
                        <p>{summary.bulan_terbaik.bulan}</p>
                        <p>
                            Rp{" "}
                            {summary.bulan_terbaik.pendapatan.toLocaleString(
                                "id-ID"
                            )}
                        </p>
                    </div>
                </div>
            )}
        </div>
    );
}
```

### Contoh Implementasi Vanilla JavaScript

```html
<!DOCTYPE html>
<html>
    <head>
        <title>Statistik Penjualan</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <canvas id="myChart"></canvas>

        <script>
            const token = "YOUR_AUTH_TOKEN";

            fetch("http://your-api.com/api/statistik/mingguan", {
                headers: {
                    Authorization: "Bearer " + token,
                    "Content-Type": "application/json",
                },
            })
                .then((response) => response.json())
                .then((result) => {
                    if (result.status) {
                        const ctx = document.getElementById("myChart");

                        new Chart(ctx, {
                            type: "line",
                            data: result.chart_data,
                            options: {
                                responsive: true,
                                interaction: {
                                    mode: "index",
                                    intersect: false,
                                },
                                plugins: {
                                    legend: {
                                        position: "top",
                                    },
                                    title: {
                                        display: true,
                                        text: "Statistik Penjualan Mingguan",
                                    },
                                },
                                scales: {
                                    y: {
                                        type: "linear",
                                        display: true,
                                        position: "left",
                                    },
                                    y1: {
                                        type: "linear",
                                        display: true,
                                        position: "right",
                                        grid: {
                                            drawOnChartArea: false,
                                        },
                                    },
                                },
                            },
                        });
                    }
                });
        </script>
    </body>
</html>
```

### Contoh Implementasi React Native

```javascript
import React, { useEffect, useState } from "react";
import { View, Text, Dimensions } from "react-native";
import { LineChart } from "react-native-chart-kit";

const StatistikMingguan = () => {
    const [chartData, setChartData] = useState(null);

    useEffect(() => {
        fetchStatistik();
    }, []);

    const fetchStatistik = async () => {
        try {
            const response = await fetch(
                "http://your-api.com/api/statistik/mingguan",
                {
                    headers: {
                        Authorization: "Bearer " + token,
                        "Content-Type": "application/json",
                    },
                }
            );

            const result = await response.json();

            if (result.status) {
                // Transform data untuk react-native-chart-kit
                const transformedData = {
                    labels: result.chart_data.labels,
                    datasets: [
                        {
                            data: result.chart_data.datasets[0].data,
                            color: (opacity = 1) =>
                                `rgba(54, 162, 235, ${opacity})`,
                            strokeWidth: 2,
                        },
                    ],
                    legend: ["Jumlah Transaksi"],
                };

                setChartData(transformedData);
            }
        } catch (error) {
            console.error("Error fetching statistik:", error);
        }
    };

    return (
        <View>
            {chartData && (
                <LineChart
                    data={chartData}
                    width={Dimensions.get("window").width - 32}
                    height={220}
                    chartConfig={{
                        backgroundColor: "#ffffff",
                        backgroundGradientFrom: "#ffffff",
                        backgroundGradientTo: "#ffffff",
                        decimalPlaces: 0,
                        color: (opacity = 1) => `rgba(0, 0, 0, ${opacity})`,
                        labelColor: (opacity = 1) =>
                            `rgba(0, 0, 0, ${opacity})`,
                        style: {
                            borderRadius: 16,
                        },
                        propsForDots: {
                            r: "6",
                            strokeWidth: "2",
                            stroke: "#ffa726",
                        },
                    }}
                    bezier
                    style={{
                        marginVertical: 8,
                        borderRadius: 16,
                    }}
                />
            )}
        </View>
    );
};

export default StatistikMingguan;
```

---

## 📋 Fitur yang Tersedia

✅ **Data siap pakai untuk Chart.js** - Format data sudah disesuaikan dengan Chart.js  
✅ **Multiple datasets** - Transaksi dan Pendapatan dalam satu chart  
✅ **Dual Y-Axis** - Sumbu Y kiri untuk transaksi, kanan untuk pendapatan  
✅ **Summary statistics** - Total, rata-rata, dan performa terbaik  
✅ **Flexible periods** - Mingguan, bulanan, dan tahunan  
✅ **Dashboard overview** - Ringkasan statistik untuk semua periode  
✅ **Custom date range** - Support parameter tanggal custom  
✅ **Zero handling** - Data otomatis 0 jika tidak ada transaksi

---

## 🔄 Tips Implementasi

1. **Refresh Data Otomatis**

```javascript
// Refresh setiap 5 menit
setInterval(() => {
    fetchStatistik();
}, 300000);
```

2. **Loading State**

```javascript
const [loading, setLoading] = useState(true);

const fetchStatistik = async () => {
    setLoading(true);
    try {
        // ... fetch code
    } finally {
        setLoading(false);
    }
};
```

3. **Error Handling**

```javascript
try {
    const response = await fetch(url);
    const result = await response.json();

    if (!result.status) {
        throw new Error(result.message);
    }

    setChartData(result.chart_data);
} catch (error) {
    console.error("Error:", error);
    alert("Gagal mengambil data statistik");
}
```

4. **Format Rupiah**

```javascript
const formatRupiah = (amount) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount);
};
```
