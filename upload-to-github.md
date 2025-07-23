# 🚀 Upload ke GitHub - Langkah Final

Repository Git sudah siap! Sekarang ikuti langkah berikut:

## 📋 Step 1: Buat Repository di GitHub

1. **Buka GitHub**: https://github.com/new
2. **Repository name**: `pengaduan-warga`
3. **Description**: `Sistem Pengaduan Warga - Web Application for Citizen Complaints with Photo Display`
4. **Visibility**: 
   - ✅ **Public** (recommended untuk GitHub Pages gratis)
   - ⚪ Private (jika ingin private)
5. **Initialize**: 
   - ❌ **JANGAN** centang "Add a README file"
   - ❌ **JANGAN** centang "Add .gitignore"
   - ❌ **JANGAN** centang "Choose a license"
6. **Click**: "Create repository"

## 📤 Step 2: Upload ke GitHub

Setelah repository dibuat, GitHub akan menampilkan halaman dengan commands. 

**COPY URL repository Anda** (contoh: `https://github.com/username/pengaduan-warga.git`)

Kemudian jalankan commands berikut di terminal:

```bash
# Username GitHub: Haruu01
git remote add origin https://github.com/Haruu01/pengaduan-warga.git

# Set branch utama
git branch -M main

# Push ke GitHub
git push -u origin main
```

## 🌐 Step 3: Enable GitHub Pages

1. **Buka repository** di GitHub
2. **Click tab "Settings"** (di bagian atas repository)
3. **Scroll ke bawah** sampai section "Pages" (di sidebar kiri)
4. **Source**: Pilih "Deploy from a branch"
5. **Branch**: Pilih "main"
6. **Folder**: Pilih "/docs"
7. **Click "Save"**

GitHub akan memproses dan memberikan URL seperti:
`https://Haruu01.github.io/pengaduan-warga`

## ✅ Step 4: Verifikasi

### **Repository GitHub:**
- ✅ Semua file terupload
- ✅ README.md tampil dengan baik
- ✅ Folder docs/ ada
- ✅ .gitignore berfungsi

### **GitHub Pages:**
- ✅ Website accessible di URL GitHub Pages
- ✅ Landing page tampil dengan baik
- ✅ Links berfungsi

## 🔧 Step 5: Update Links (Setelah Upload)

Setelah repository dibuat, update links di file berikut:

### **✅ SUDAH DIUPDATE:**
Semua links sudah diupdate dengan username **Haruu01**:
- `https://github.com/Haruu01/pengaduan-warga`
- `https://github.com/Haruu01/pengaduan-warga/archive/main.zip`
- `https://Haruu01.github.io/pengaduan-warga`

### **✅ Files Updated:**
- `docs/index.html` ✅
- `docs/_config.yml` ✅
- `docs/README.md` ✅

### **docs/README.md:**
Update semua links dengan username yang benar.

## 📊 Commands untuk Update Links

Setelah upload, jalankan commands ini untuk update links:

```bash
# Update dan push perubahan
git add .
git commit -m "Update GitHub Pages links with correct username"
git push origin main
```

## 🎯 Final Result

Setelah semua langkah selesai, Anda akan memiliki:

### **GitHub Repository:**
`https://github.com/Haruu01/pengaduan-warga`
- ✅ Source code lengkap
- ✅ Documentation
- ✅ Database files
- ✅ Deployment guides

### **GitHub Pages Website:**
`https://Haruu01.github.io/pengaduan-warga`
- ✅ Landing page profesional
- ✅ Feature showcase
- ✅ Download links
- ✅ Documentation links

### **Features:**
- ✅ Responsive design
- ✅ Professional presentation
- ✅ Easy download access
- ✅ Complete documentation

## 🆘 Troubleshooting

### **Jika Git Push Error:**
```bash
# Jika ada authentication error
git config --global credential.helper store

# Jika branch conflict
git pull origin main --allow-unrelated-histories
git push origin main
```

### **Jika GitHub Pages tidak muncul:**
- Wait 5-10 minutes untuk processing
- Check Settings > Pages configuration
- Pastikan folder "/docs" dipilih
- Check repository visibility (public untuk Pages gratis)

## 📞 Need Help?

Jika ada masalah:
1. Check error messages di terminal
2. Verify repository settings di GitHub
3. Wait beberapa menit untuk GitHub Pages processing
4. Check GitHub status page jika ada issues

---

**Ready to upload! 🚀**

**Next**: Buat repository di GitHub dan jalankan commands di atas!
