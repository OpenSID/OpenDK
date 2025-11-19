# 📚 Dokumentasi Testing OpenDK dengan Pest 4

Selamat datang di dokumentasi testing OpenDK! Dokumentasi ini dibuat untuk membantu tim programmer memahami dan menggunakan Pest 4 dalam development aplikasi OpenDK.

## 📖 Daftar Dokumentasi

### 1. [TESTING_DENGAN_PEST4.md](./TESTING_DENGAN_PEST4.md)
**Dokumentasi Lengkap Pest 4**

Dokumentasi komprehensif tentang Pest 4 yang mencakup:
- ✅ Pengenalan Pest 4
- ✅ Instalasi dan setup
- ✅ Struktur direktori testing
- ✅ Jenis-jenis testing (Unit, Feature, Browser)
- ✅ Menulis test dengan Pest 4
- ✅ Menjalankan test
- ✅ Best practices
- ✅ Troubleshooting

**Untuk siapa:** Semua programmer, terutama yang baru mengenal Pest

**Kapan digunakan:** Sebagai referensi utama dan panduan lengkap

---

### 2. [PEST_CHEATSHEET.md](./PEST_CHEATSHEET.md)
**Quick Reference Pest 4**

Cheatsheet praktis yang berisi:
- ✅ Syntax test
- ✅ Expectations API
- ✅ Laravel specific helpers
- ✅ Database testing
- ✅ HTTP testing
- ✅ Authentication
- ✅ Commands reference
- ✅ Tips & tricks

**Untuk siapa:** Programmer yang sudah familiar dengan Pest

**Kapan digunakan:** Quick reference saat development, copy-paste code snippets

---

### 3. [CONTOH_TESTING_OPENDK.md](./CONTOH_TESTING_OPENDK.md)
**Contoh Testing untuk OpenDK**

Contoh-contoh praktis testing spesifik untuk aplikasi OpenDK:
- ✅ Testing CRUD Artikel
- ✅ Testing API endpoints
- ✅ Testing Export data
- ✅ Testing Authentication & Authorization
- ✅ Testing File upload
- ✅ Testing Data Desa
- ✅ Testing Dashboard
- ✅ Testing Browser/E2E
- ✅ Testing Services
- ✅ Testing dengan Dataset

**Untuk siapa:** Semua programmer yang develop fitur OpenDK

**Kapan digunakan:** Saat menulis test untuk fitur baru atau bug fix

---

### 4. [WORKFLOW_TESTING.md](./WORKFLOW_TESTING.md)
**Workflow Testing dalam Tim**

Panduan workflow testing untuk tim development:
- ✅ Setup environment testing
- ✅ Development workflow (TDD approach)
- ✅ Code review checklist
- ✅ Continuous Integration setup
- ✅ Testing strategy
- ✅ Team agreement & guidelines
- ✅ Troubleshooting common issues

**Untuk siapa:** Semua anggota tim, tech lead, reviewer

**Kapan digunakan:** Setup project, development workflow, code review

---

## 🚀 Quick Start

### Pertama Kali Setup

1. **Baca dokumentasi utama:**
   - [TESTING_DENGAN_PEST4.md](./TESTING_DENGAN_PEST4.md)

2. **Setup environment:**
   - Follow [Workflow Testing - Persiapan Environment](./WORKFLOW_TESTING.md#persiapan-environment)

3. **Jalankan test pertama:**
   ```bash
   ./vendor/bin/pest tests/Feature/ExampleTest.php
   ```

### Development Harian

1. **Check cheatsheet untuk syntax:**
   - [PEST_CHEATSHEET.md](./PEST_CHEATSHEET.md)

2. **Lihat contoh yang mirip:**
   - [CONTOH_TESTING_OPENDK.md](./CONTOH_TESTING_OPENDK.md)

3. **Follow workflow:**
   - [WORKFLOW_TESTING.md](./WORKFLOW_TESTING.md#workflow-development)

---

## 🎯 Learning Path

### Level 1: Beginner (Minggu 1-2)
**Goal:** Memahami dasar-dasar testing dengan Pest

1. Baca [TESTING_DENGAN_PEST4.md](./TESTING_DENGAN_PEST4.md) - Section:
   - Pengenalan
   - Instalasi dan Setup
   - Jenis-jenis Testing
   - Menulis Test dengan Pest 4 (Basic)

2. Praktek:
   - Setup environment
   - Jalankan existing tests
   - Modifikasi simple test
   - Buat test sederhana

3. Referensi:
   - [PEST_CHEATSHEET.md](./PEST_CHEATSHEET.md) - Test Syntax & Expectations

**Output:** Bisa membuat test sederhana untuk happy path

---

### Level 2: Intermediate (Minggu 3-4)
**Goal:** Menulis test untuk fitur real

1. Baca [CONTOH_TESTING_OPENDK.md](./CONTOH_TESTING_OPENDK.md) - Section:
   - Testing CRUD
   - Testing API
   - Testing dengan Dataset

2. Baca [WORKFLOW_TESTING.md](./WORKFLOW_TESTING.md) - Section:
   - Development Workflow
   - Testing Strategy

3. Praktek:
   - Menulis test untuk fitur baru
   - Menulis test untuk bug fix
   - Implementasi TDD

4. Referensi:
   - [TESTING_DENGAN_PEST4.md](./TESTING_DENGAN_PEST4.md) - Best Practices
   - [PEST_CHEATSHEET.md](./PEST_CHEATSHEET.md) - HTTP Testing & Database

**Output:** Bisa membuat test lengkap (happy path + validation + edge cases)

---

### Level 3: Advanced (Minggu 5+)
**Goal:** Master testing & maintain test quality

1. Baca semua dokumentasi section advanced:
   - Browser/E2E Testing
   - Testing Services
   - Advanced Patterns

2. Baca [WORKFLOW_TESTING.md](./WORKFLOW_TESTING.md) - All sections

3. Praktek:
   - Code review test orang lain
   - Setup CI/CD
   - Improve test coverage
   - Refactoring dengan test

4. Contribution:
   - Membuat contoh test baru
   - Update dokumentasi
   - Membantu tim lain

**Output:** Bisa review test, guide tim, maintain test quality

---

## 📊 Struktur Testing OpenDK

```
tests/
├── Pest.php                      # Konfigurasi Pest
├── TestCase.php                  # Base test case
├── CreatesApplication.php        # Bootstrap app
│
├── Unit/                         # Unit tests (60%)
│   ├── Services/                 # Test services
│   ├── Helpers/                  # Test helper functions
│   └── Models/                   # Test model methods
│
├── Feature/                      # Feature tests (30%)
│   ├── Api/                      # API endpoint tests
│   │   └── ArtikelApiTest.php
│   ├── Admin/                    # Admin feature tests
│   │   ├── ArtikelTest.php
│   │   └── DataDesaTest.php
│   └── ExportTest.php
│
└── Browser/                      # Browser tests (10%)
    ├── Auth/                     # Authentication flows
    │   └── LoginTest.php
    └── CriticalFlows/            # Critical user flows
        └── CheckoutTest.php
```

---

## ⚡ Command Reference

### Daily Commands

```bash
# Jalankan semua test
./vendor/bin/pest

# Jalankan test untuk fitur yang sedang dikerjakan
./vendor/bin/pest tests/Feature/ArtikelTest.php

# Jalankan dengan filter
./vendor/bin/pest --filter="artikel"

# Skip browser test (lebih cepat)
./vendor/bin/pest --exclude-group=browser

# Check coverage
./vendor/bin/pest --coverage

# Before commit
./vendor/bin/pest && ./vendor/bin/pint
```

### Advanced Commands

```bash
# Parallel execution (faster)
./vendor/bin/pest --parallel

# Profile slow tests
./vendor/bin/pest --profile

# CI mode
./vendor/bin/pest --ci --parallel

# Watch mode (auto re-run)
./vendor/bin/pest --watch
```

---

## 🔍 Troubleshooting

### Test Gagal?

1. **Check error message:**
   ```bash
   ./vendor/bin/pest -v
   ```

2. **Lihat troubleshooting guide:**
   - [TESTING_DENGAN_PEST4.md - Troubleshooting](./TESTING_DENGAN_PEST4.md#troubleshooting)
   - [WORKFLOW_TESTING.md - Troubleshooting](./WORKFLOW_TESTING.md#troubleshooting-common-issues)

3. **Tanya tim:**
   - Buat issue di GitHub
   - Diskusi di channel Slack/Discord

### Setup Issues?

1. **Verify setup:**
   ```bash
   ./vendor/bin/pest --version
   php artisan --version
   ```

2. **Check environment:**
   ```bash
   php artisan config:clear
   php artisan migrate --env=testing
   ```

3. **Follow setup guide:**
   - [WORKFLOW_TESTING.md - Persiapan Environment](./WORKFLOW_TESTING.md#persiapan-environment)

---

## 📈 Coverage Target

### Target Coverage per Module

| Module | Unit | Feature | Browser | Target |
|--------|------|---------|---------|--------|
| Core (Artikel, User) | 90% | 80% | ✓ | 85% |
| Data Desa | 85% | 75% | - | 80% |
| Export | 80% | 70% | - | 75% |
| API | 85% | 80% | - | 80% |
| Admin Panel | 75% | 70% | ✓ | 70% |

### Check Coverage

```bash
# Check overall coverage
./vendor/bin/pest --coverage

# With minimum threshold
./vendor/bin/pest --coverage --min=70

# Generate HTML report
./vendor/bin/pest --coverage --coverage-html=coverage
```

---

## 🤝 Contributing

### Menambah Dokumentasi

1. Fork & clone repository
2. Tambah/update dokumentasi
3. Submit PR dengan label `documentation`

### Menambah Contoh Test

1. Buat test yang lengkap dan well-documented
2. Tambahkan ke [CONTOH_TESTING_OPENDK.md](./CONTOH_TESTING_OPENDK.md)
3. Submit PR

### Melaporkan Issue

1. Cek existing issues
2. Buat issue baru dengan template
3. Tag: `testing`, `documentation`, `help-wanted`

---

## 📞 Support

### Dokumentasi
- [Pest Official Docs](https://pestphp.com)
- [Laravel Testing Docs](https://laravel.com/docs/testing)

### OpenDK Team
- GitHub Issues: [OpenSID/OpenDK](https://github.com/OpenSID/opendk)
- Website: [OpenDK.my.id](https://opendk.my.id)

---

## 📝 Changelog

### Version 1.0 (November 2025)
- ✅ Initial documentation
- ✅ Complete Pest 4 guide
- ✅ Cheatsheet
- ✅ OpenDK specific examples
- ✅ Workflow guide

---

## 📄 License

Dokumentasi ini adalah bagian dari OpenDK yang dirilis dengan lisensi GPL V3.

---

**Dibuat dengan ❤️ oleh Tim Pengembang OpenDesa**  
**Last Updated:** November 2025  
**Version:** 1.0
