# API Dokumentasi - Laporan Transaksi Penjualan

## Endpoint yang Tersedia

### 1. Laporan Periode (JSON)

**Endpoint:** `POST /api/laporan/periode`  
**Auth:** Bearer Token (Sanctum)

**Request Body:**

```json
{
    "tanggal_awal": "2025-01-01",
    "tanggal_akhir": "2025-01-31"
}
```

**Response:**

```json
{
  "status": true,
  "message": "Laporan transaksi periode berhasil diambil.",
  "periode": {
    "tanggal_awal": "2025-01-01",
    "tanggal_akhir": "2025-01-31"
  },
  "ringkasan": {
    "total_transaksi": 10,
    "total_pendapatan": 500000,
    "total_item_terjual": 25
  },
  "data": [...]
}
```

---

### 2. Laporan Periode (PDF)

**Endpoint:** `POST /api/laporan/periode/pdf`  
**Auth:** Bearer Token (Sanctum)

**Request Body:**

```json
{
    "tanggal_awal": "2025-01-01",
    "tanggal_akhir": "2025-01-31"
}
```

**Response:** File PDF (Auto Download)

-   Filename: `Laporan_Transaksi_Periode_20250101_20250131.pdf`

---

### 3. Laporan Bulanan (JSON)

**Endpoint:** `POST /api/laporan/bulanan`  
**Auth:** Bearer Token (Sanctum)

**Request Body:**

```json
{
    "bulan": 10,
    "tahun": 2025
}
```

**Response:**

```json
{
  "status": true,
  "message": "Laporan transaksi bulanan berhasil diambil.",
  "periode": {
    "bulan": 10,
    "tahun": 2025,
    "nama_bulan": "Oktober",
    "tanggal_awal": "2025-10-01",
    "tanggal_akhir": "2025-10-31"
  },
  "ringkasan": {
    "total_transaksi": 15,
    "total_pendapatan": 750000,
    "total_item_terjual": 40
  },
  "data_per_tanggal": [...]
}
```

---

### 4. Laporan Bulanan (PDF)

**Endpoint:** `POST /api/laporan/bulanan/pdf`  
**Auth:** Bearer Token (Sanctum)

**Request Body:**

```json
{
    "bulan": 10,
    "tahun": 2025
}
```

**Response:** File PDF (Auto Download)

-   Filename: `Laporan_Transaksi_Bulanan_Oktober_2025.pdf`

---

### 5. Laporan Produk Terlaris (JSON)

**Endpoint:** `POST /api/laporan/produk-terlaris`  
**Auth:** Bearer Token (Sanctum)

**Request Body:**

```json
{
    "tanggal_awal": "2025-01-01",
    "tanggal_akhir": "2025-01-31",
    "limit": 10
}
```

**Response:**

```json
{
    "status": true,
    "message": "Laporan produk terlaris berhasil diambil.",
    "periode": {
        "tanggal_awal": "2025-01-01",
        "tanggal_akhir": "2025-01-31"
    },
    "data": [
        {
            "id": 1,
            "kode_produk": "PRD001",
            "nama_produk": "Vitamin C",
            "harga": "50000.00",
            "total_terjual": "100",
            "total_pendapatan": "5000000.00",
            "total_transaksi": "25"
        }
    ]
}
```

---

### 6. Laporan Produk Terlaris (PDF)

**Endpoint:** `POST /api/laporan/produk-terlaris/pdf`  
**Auth:** Bearer Token (Sanctum)

**Request Body:**

```json
{
    "tanggal_awal": "2025-01-01",
    "tanggal_akhir": "2025-01-31",
    "limit": 10
}
```

**Response:** File PDF (Auto Download)

-   Filename: `Laporan_Produk_Terlaris_20250101_20250131.pdf`

---

## Fitur PDF

### Laporan Periode PDF

-   **Format:** A4 Portrait
-   **Konten:**
    -   Header dengan periode laporan
    -   Ringkasan total transaksi, item terjual, dan pendapatan
    -   Detail setiap transaksi dengan item produk
    -   Total per transaksi

### Laporan Bulanan PDF

-   **Format:** A4 Portrait
-   **Konten:**
    -   Header dengan bulan dan tahun
    -   Ringkasan bulanan
    -   Data dikelompokkan per tanggal
    -   Detail transaksi per hari
    -   Total per transaksi

### Laporan Produk Terlaris PDF

-   **Format:** A4 Landscape
-   **Konten:**
    -   Header dengan periode laporan
    -   Tabel produk terlaris dengan ranking
    -   Highlight peringkat 1-3
    -   Total keseluruhan penjualan

---

## Validasi

### Laporan Periode

-   `tanggal_awal`: Required, format date
-   `tanggal_akhir`: Required, format date, harus >= tanggal_awal

### Laporan Bulanan

-   `bulan`: Required, integer, range 1-12
-   `tahun`: Required, integer, range 2000-2100

### Laporan Produk Terlaris

-   `tanggal_awal`: Required, format date
-   `tanggal_akhir`: Required, format date, harus >= tanggal_awal
-   `limit`: Optional, integer, range 1-100, default: 10

---

## Cara Menggunakan

### Contoh dengan cURL (JSON Response)

```bash
curl -X POST "http://localhost/api/laporan/periode" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "tanggal_awal": "2025-01-01",
    "tanggal_akhir": "2025-01-31"
  }'
```

### Contoh dengan cURL (PDF Download)

```bash
curl -X POST "http://localhost/api/laporan/periode/pdf" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "tanggal_awal": "2025-01-01",
    "tanggal_akhir": "2025-01-31"
  }' \
  --output laporan.pdf
```

### Contoh dengan JavaScript (Fetch API)

```javascript
// JSON Response
fetch("http://localhost/api/laporan/periode", {
    method: "POST",
    headers: {
        Authorization: "Bearer YOUR_TOKEN_HERE",
        "Content-Type": "application/json",
    },
    body: JSON.stringify({
        tanggal_awal: "2025-01-01",
        tanggal_akhir: "2025-01-31",
    }),
})
    .then((response) => response.json())
    .then((data) => console.log(data));

// PDF Download
fetch("http://localhost/api/laporan/periode/pdf", {
    method: "POST",
    headers: {
        Authorization: "Bearer YOUR_TOKEN_HERE",
        "Content-Type": "application/json",
    },
    body: JSON.stringify({
        tanggal_awal: "2025-01-01",
        tanggal_akhir: "2025-01-31",
    }),
})
    .then((response) => response.blob())
    .then((blob) => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "laporan.pdf";
        a.click();
    });
```

---

## Error Response

```json
{
    "status": false,
    "message": "Validasi gagal.",
    "errors": {
        "tanggal_awal": ["The tanggal awal field is required."]
    }
}
```

atau

```json
{
    "status": false,
    "message": "Tidak ada transaksi pada periode yang dipilih."
}
```
