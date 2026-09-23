-- ==========================================
-- SKEMA DATABASE OPERASIONAL DARAGO
-- ==========================================

-- Hapus tabel jika sudah ada (berguna untuk reset/testing)
DROP TABLE IF EXISTS pengumuman_livechat CASCADE;
DROP TABLE IF EXISTS log_lokasi CASCADE;
DROP TABLE IF EXISTS dokumentasi_trip CASCADE;
DROP TABLE IF EXISTS fasilitas_peserta CASCADE;
DROP TABLE IF EXISTS jadwal_acara CASCADE;
DROP TABLE IF EXISTS destinasi_wisata CASCADE;
DROP TABLE IF EXISTS manifest_peserta CASCADE;
DROP TABLE IF EXISTS armada_bus CASCADE;
DROP TABLE IF EXISTS trip_perjalanan CASCADE;
DROP TABLE IF EXISTS peserta_klien CASCADE;
DROP TABLE IF EXISTS karyawan CASCADE;
DROP TABLE IF EXISTS biro_perjalanan CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ==========================================
-- 1. MODUL AUTENTIKASI & PENGGUNA
-- ==========================================

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    role VARCHAR(50) NOT NULL, -- Admin, TourLeader, Supir, Peserta
    device_token TEXT -- Untuk push notification
);

CREATE TABLE biro_perjalanan (
    id SERIAL PRIMARY KEY,
    nama_biro VARCHAR(255) NOT NULL,
    alamat TEXT,
    kontak VARCHAR(50),
    logo_url TEXT
);

CREATE TABLE karyawan (
    id SERIAL PRIMARY KEY,
    user_id INT UNIQUE NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    biro_id INT NOT NULL REFERENCES biro_perjalanan(id) ON DELETE CASCADE,
    posisi VARCHAR(100) NOT NULL -- Tour Leader, Supir, Dokumentasi
);

CREATE TABLE peserta_klien (
    id SERIAL PRIMARY KEY,
    user_id INT UNIQUE NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    nama_instansi VARCHAR(255) NOT NULL,
    kontak_darurat VARCHAR(50),
    catatan_medis TEXT -- Alergi, penyakit bawaan
);

-- ==========================================
-- 2. MODUL INTI OPERASIONAL
-- ==========================================

CREATE TABLE trip_perjalanan (
    id SERIAL PRIMARY KEY,
    biro_id INT NOT NULL REFERENCES biro_perjalanan(id) ON DELETE CASCADE,
    nama_kegiatan VARCHAR(255) NOT NULL,
    tgl_mulai DATE NOT NULL,
    tgl_selesai DATE NOT NULL,
    status VARCHAR(50) DEFAULT 'Upcoming' -- Upcoming, Ongoing, Completed
);

CREATE TABLE armada_bus (
    id SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip_perjalanan(id) ON DELETE CASCADE,
    nama_po VARCHAR(150),
    nomor_bus VARCHAR(50) NOT NULL,
    supir_id INT REFERENCES karyawan(id) ON DELETE SET NULL,
    tour_leader_id INT REFERENCES karyawan(id) ON DELETE SET NULL
);

CREATE TABLE manifest_peserta (
    id SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip_perjalanan(id) ON DELETE CASCADE,
    armada_id INT NOT NULL REFERENCES armada_bus(id) ON DELETE CASCADE,
    peserta_id INT NOT NULL REFERENCES peserta_klien(id) ON DELETE CASCADE,
    nomor_kursi VARCHAR(10),
    status_presensi BOOLEAN DEFAULT FALSE
);

-- ==========================================
-- 3. MODUL ITINERARY & NAVIGASI
-- ==========================================

CREATE TABLE destinasi_wisata (
    id SERIAL PRIMARY KEY,
    nama_tempat VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    aturan_lokal TEXT,
    latitude DOUBLE PRECISION,
    longitude DOUBLE PRECISION
);

CREATE TABLE jadwal_acara (
    id SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip_perjalanan(id) ON DELETE CASCADE,
    destinasi_id INT REFERENCES destinasi_wisata(id) ON DELETE SET NULL,
    waktu_mulai TIMESTAMP NOT NULL,
    waktu_selesai TIMESTAMP NOT NULL,
    kegiatan VARCHAR(255) NOT NULL
);

-- ==========================================
-- 4. MODUL FASILITAS & DOKUMENTASI
-- ==========================================

CREATE TABLE fasilitas_peserta (
    id SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip_perjalanan(id) ON DELETE CASCADE,
    peserta_id INT NOT NULL REFERENCES peserta_klien(id) ON DELETE CASCADE,
    kategori VARCHAR(100) NOT NULL, -- Kamar Hotel, Voucher Makan
    detail_fasilitas VARCHAR(255) NOT NULL -- No Kamar / Kode Voucher
);

CREATE TABLE dokumentasi_trip (
    id SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip_perjalanan(id) ON DELETE CASCADE,
    url_media TEXT NOT NULL,
    keterangan TEXT,
    waktu_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- 5. MODUL PEMANTAUAN & KOMUNIKASI
-- ==========================================

CREATE TABLE log_lokasi (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    trip_id INT NOT NULL REFERENCES trip_perjalanan(id) ON DELETE CASCADE,
    latitude DOUBLE PRECISION NOT NULL,
    longitude DOUBLE PRECISION NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tipe_entitas VARCHAR(50) NOT NULL -- Bus, Supir, Peserta
);

CREATE TABLE pengumuman_livechat (
    id SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip_perjalanan(id) ON DELETE CASCADE,
    armada_id INT REFERENCES armada_bus(id) ON DELETE CASCADE,
    pesan_pengumuman TEXT NOT NULL,
    waktu_dikirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
